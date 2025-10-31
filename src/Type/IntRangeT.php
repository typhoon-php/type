<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T of int = int
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class IntRangeT implements Type
{
    public function __construct(
        public int $min = PHP_INT_MIN,
        public int $max = PHP_INT_MAX,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intRangeT($this);
    }
}
