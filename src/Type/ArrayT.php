<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant K of array-key = array-key
 * @template-covariant V = mixed
 * @implements Type<array<K, V>>
 * @codeCoverageIgnore
 */
final readonly class ArrayT implements Type
{
    /**
     * @param Type<K> $keyType
     * @param Type<V> $valueType
     * @param list<ArrayElement> $elements
     */
    public function __construct(
        public Type $keyType = ArrayKeyT::T,
        public Type $valueType = MixedT::T,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->arrayT($this);
    }
}
