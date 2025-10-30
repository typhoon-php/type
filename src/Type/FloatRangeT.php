<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Brick\Math\BigDecimal;
use Typhoon\Type;

/**
 * @api
 * @template-covariant T of float = float
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class FloatRangeT implements Type
{
    public function __construct(
        public ?BigDecimal $min = null,
        public ?BigDecimal $max = null,
    ) {
        if ($min !== null && $max !== null && $min->isGreaterThan($max)) {
            throw new \ValueError(\sprintf(
                '`%s` requires min to be less than or equal to max, got min=%d, max=%d',
                self::class,
                $min,
                $max,
            ));
        }
    }

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatRangeT($this);
    }
}
