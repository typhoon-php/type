<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<list<mixed>>
 */
final class ListT implements Type
{
    public readonly Type $valueType;

    /** @var array<non-negative-int, ArrayElement> */
    public readonly array $elements;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param array<non-negative-int, ArrayElement> $elements
     */
    public function __construct(Type $valueType, array $elements)
    {
        $this->valueType = $valueType;
        $this->elements = $elements;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->list($this);
    }
}
