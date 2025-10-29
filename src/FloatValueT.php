<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Brick\Math\BigNumber;

/**
 * @api
 * @template-covariant T of float = float
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class FloatValueT implements Type
{
    public function __construct(
        public BigNumber $value,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatValueT($this);
    }
}
