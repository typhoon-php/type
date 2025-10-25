<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @template-covariant K = mixed
 * @implements Type<T[K]>
 */
final readonly class OffsetT implements Type
{
    /**
     * @param Type<T> $array
     * @param Type<K> $key
     */
    public function __construct(
        public Type $array,
        public Type $key,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->offset($this);
    }
}
