<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of float = float
 * @implements Type<T>
 */
final readonly class FloatRangeT implements Type
{
    /**
     * @param ?numeric-string $min
     * @param ?numeric-string $max
     */
    public function __construct(
        public ?string $min = null,
        public ?string $max = null,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatRangeT($this);
    }
}
