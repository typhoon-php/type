<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant K of array-key = array-key
 * @template-covariant V = mixed
 * @implements Type<array<K, V>>
 */
final readonly class ArrayT implements Type
{
    /**
     * @param Type<K> $key
     * @param Type<V> $value
     * @param array<ArrayElement> $elements
     */
    public function __construct(
        public Type $key = ArrayKeyT::T,
        public Type $value = MixedT::T,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->arrayT($this);
    }
}
