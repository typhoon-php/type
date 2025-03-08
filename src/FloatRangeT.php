<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<float>
 */
final class FloatRangeT implements Type
{
    public readonly ?float $min;

    public readonly ?float $max;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(?float $min, ?float $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->floatRange($this);
    }
}
