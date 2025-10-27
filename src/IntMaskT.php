<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of int = int
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class IntMaskT implements Type
{
    public function __construct(
        public Type $ints,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intMaskT($this);
    }
}
