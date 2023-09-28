<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 * @template-covariant TArray of array
 * @implements Type<TArray>
 */
final class ArrayShapeType implements Type
{
    /**
     * @var array<ArrayElement>
     */
    public readonly array $elements;

    public readonly bool $sealed;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param array<ArrayElement> $elements
     */
    public function __construct(
        array $elements = [],
        bool $sealed = true,
    ) {
        $this->sealed = $sealed;
        $this->elements = $elements;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitShape($this);
    }
}
