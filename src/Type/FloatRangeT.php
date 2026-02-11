<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of float = float
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class FloatRangeT implements Type
{
    public function __construct(
        public ?float $min = null,
        public ?float $max = null,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatRangeT($this);
    }
}
