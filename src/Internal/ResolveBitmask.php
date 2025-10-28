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
    public function intValueT(IntValueT $type): mixed
    {
        return $type->value;
    }

    public function intT(IntT $type): mixed
    {
        return -1;
    }

    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        return -1;
    }

    public function positiveIntT(PositiveIntT $type): mixed
    {
        return -1;
    }

    public function negativeIntT(NegativeIntT $type): mixed
    {
        return -1;
    }

    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        return -1;
    }

    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        return -1;
    }

    public function intRangeT(IntRangeT $type): mixed
    {
        // todo optimize

        $mask = 0;

        for ($int = $type->min ?? PHP_INT_MIN; $int <= ($type->max ?? PHP_INT_MAX); ++$int) {
            $mask |= $int;
        }

        return $mask;
    }

    public function unionT(UnionT $type): mixed
    {
        $mask = 0;

        foreach ($type->types as $type) {
            $mask |= $type->accept($this);
        }

        return $mask;
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        $mask = -1;

        foreach ($type->types as $type) {
            $mask &= $type->accept($this);
        }

        return $mask;
    }

    protected function fallback(Type $type): mixed
    {
        throw new \LogicException();
    }
}
