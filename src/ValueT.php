<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<value-of<T>>
 */
final readonly class ValueT implements Type
{
    /**
     * @param Type<T> $array
     */
    public function __construct(
        public Type $array,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->valueT($this);
    }
}
