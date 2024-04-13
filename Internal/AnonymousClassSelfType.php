<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\Visitor\AnonymousClassTypesResolver;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @readonly
 * @implements Type<object>
 */
final class AnonymousClassSelfType implements Type
{
    /**
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly array $arguments,
    ) {}

    /**
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function accept(TypeVisitor $visitor): mixed
    {
        if ($visitor instanceof AnonymousClassTypesResolver) {
            /** @phpstan-ignore return.type */
            return $visitor->objectType;
        }

        return $visitor->alias($this, 'self', 'anonymous-class', $this->arguments);
    }
}
