<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\DeclarationId\ClassId;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @psalm-immutable
 * @implements Type<object>
 */
final class SelfType implements Type
{
    /**
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly ?ClassId $resolvedClass,
        private readonly array $arguments,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->self($this, $this->resolvedClass, $this->arguments);
    }
}
