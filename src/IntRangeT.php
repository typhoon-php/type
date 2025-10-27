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
 */
final readonly class IntRangeT implements Type
{
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intRangeT($this);
    }
}
