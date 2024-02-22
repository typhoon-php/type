<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @template-covariant TInt of int
 * @implements Type<TInt>
 */
final class IntRangeType implements Type
{
    public function __construct(
        private readonly ?int $min,
        private readonly ?int $max,
    ) {
        \assert($min !== null || $max !== null, 'Int range type must have at least one limit defined.');
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->intRange($this, $this->min, $this->max);
    }
}
