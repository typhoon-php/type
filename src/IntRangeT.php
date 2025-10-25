<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<int>
 */
final readonly class IntRangeT implements Type
{
    /**
     * @param null|int|numeric-string $min
     * @param null|int|numeric-string $max
     */
    public function __construct(
        public null|int|string $min = null,
        public null|int|string $max = null,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intRange($this);
    }
}
