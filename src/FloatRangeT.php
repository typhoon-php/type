<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<float>
 */
final readonly class FloatRangeT implements Type
{
    /**
     * @param null|int|float|numeric-string $min
     * @param null|int|float|numeric-string $max
     */
    public function __construct(
        public null|int|float|string $min = null,
        public null|int|float|string $max = null,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatRange($this);
    }
}
