<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\Visitor\TraitTypesResolver;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @readonly
 * @implements Type<object>
 */
final class TraitStaticType implements Type
{
    /**
     * @param non-empty-string $trait
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly string $trait,
        private readonly array $arguments,
    ) {}

    /**
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function accept(TypeVisitor $visitor): mixed
    {
        if ($visitor instanceof TraitTypesResolver) {
            /** @phpstan-ignore return.type */
            return $visitor->traitStatic($this->arguments);
        }

        return $visitor->alias($this, 'static', $this->trait, $this->arguments);
    }
}
