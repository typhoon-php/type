<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor\Fallback;

/**
 * @internal
 * @extends Fallback<int>
 */
final class ResolveBitmask extends Fallback
{
    public function intValueT(IntValueT $type): int
    {
        return $type->value;
    }

    public function intT(IntT $type): int
    {
        return -1;
    }

    public function nonZeroIntT(NonZeroIntT $type): int
    {
        return -1;
    }

    public function positiveIntT(PositiveIntT $type): int
    {
        return -1;
    }

    public function negativeIntT(NegativeIntT $type): int
    {
        return -1;
    }

    public function nonNegativeIntT(NonNegativeIntT $type): int
    {
        return -1;
    }

    public function nonPositiveIntT(NonPositiveIntT $type): int
    {
        return -1;
    }

    // todo intRangeT

    public function unionT(UnionT $type): int
    {
        $mask = 0;

        foreach ($type->types as $type) {
            $mask |= $type->accept($this);
        }

        return $mask;
    }

    public function intersectionT(IntersectionT $type): int
    {
        $mask = -1;

        foreach ($type->types as $type) {
            $mask &= $type->accept($this);
        }

        return $mask;
    }

    protected function fallback(Type $type): never
    {
        throw new \LogicException();
    }
}
