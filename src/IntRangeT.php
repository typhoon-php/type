<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<int>
 */
final class IntRangeT implements Type
{
    public readonly ?int $min;

    public readonly ?int $max;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(?int $min, ?int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->intRange($this);
    }
}
