<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @template-covariant K = mixed
 * @implements Type<T[K]>
 * @codeCoverageIgnore
 */
final readonly class OffsetT implements Type
{
    /**
     * @param Type<T> $arrayType
     * @param Type<K> $keyType
     */
    public function __construct(
        public Type $arrayType,
        public Type $keyType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->offsetT($this);
    }
}
