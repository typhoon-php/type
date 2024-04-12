<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @psalm-immutable
 * @implements Type<object>
 */
final class StaticType implements Type
{
    /**
     * @param non-empty-string $class
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly string $class,
        private readonly array $arguments,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->static($this, $this->class, $this->arguments);
    }
}
