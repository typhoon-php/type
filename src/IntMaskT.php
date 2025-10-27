<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of int = int
 * @implements Type<int-mask-of<T>>
 */
final readonly class IntMaskT implements Type
{
    /**
     * @param Type<T> $ints
     */
    public function __construct(
        public Type $ints,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intMaskT($this);
    }
}
