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
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatRangeT($this);
    }
}
