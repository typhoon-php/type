<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant K = mixed
 * @template-covariant V = mixed
 * @implements Type<iterable<K, V>>
 * @codeCoverageIgnore
 */
final readonly class IterableT implements Type
{
    /**
     * @param Type<K> $keyType
     * @param Type<V> $valueType
     */
    public function __construct(
        public Type $keyType = MixedT::T,
        public Type $valueType = MixedT::T,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->iterableT($this);
    }
}
