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
    /** @var ?numeric-string */
    public readonly ?string $min;

    /** @var ?numeric-string */
    public readonly ?string $max;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param ?numeric-string $min
     * @param ?numeric-string $max
     */
    public function __construct(?string $min, ?string $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->floatRange($this);
    }
}
