<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<array<mixed>>
 */
final class ArrayT implements Type
{
    public readonly Type $keyType;

    public readonly Type $valueType;

    /** @var array<ArrayElement> */
    public readonly array $elements;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param array<ArrayElement> $elements
     */
    public function __construct(Type $keyType, Type $valueType, array $elements)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
        $this->elements = $elements;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->array($this);
    }
}
