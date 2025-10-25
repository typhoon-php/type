<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\ArrayOpenT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableOpenT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableOpenT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectOpenT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\VoidT;
use function Typhoon\Type\Internal\floatToString;
use function Typhoon\Type\stringify;

/**
 * @api
 * @extends Fallback<bool>
 */
abstract class Is extends Fallback
{
    public function __construct(
        private readonly mixed $value,
    ) {}

    public function never(NeverT $type): mixed
    {
        return false;
    }

    public function void(VoidT $type): mixed
    {
        return false;
    }

    public function null(NullT $type): mixed
    {
        return $this->value === null;
    }

    public function false(FalseT $type): mixed
    {
        return $this->value === false;
    }

    public function true(TrueT $type): mixed
    {
        return $this->value === true;
    }

    public function bool(BoolT $type): mixed
    {
        return \is_bool($this->value);
    }

    public function int(IntT $type): mixed
    {
        return \is_int($this->value);
    }

    public function intValue(IntValueT $type): mixed
    {
        return $this->value === $type->value;
    }

    public function intRange(IntRangeT $type): mixed
    {
        return \is_int($this->value)
            && ($type->min === null || $this->value >= $type->min)
            && ($type->max === null || $this->value <= $type->max);
    }

    public function nonZeroInt(NonZeroIntT $type): mixed
    {
        return \is_int($this->value) && $this->value !== 0;
    }

    public function float(FloatT $type): mixed
    {
        return \is_float($this->value);
    }

    public function floatValue(FloatValueT $type): mixed
    {
        return \is_float($this->value) && floatToString($this->value) === $type->value;
    }

    public function string(StringT $type): mixed
    {
        return \is_string($this->value);
    }

    public function nonEmptyString(NonEmptyStringT $type): mixed
    {
        return \is_string($this->value) && $this->value !== '';
    }

    public function truthyString(TruthyStringT $type): mixed
    {
        return \is_string($this->value) && $this->value;
    }

    public function numericString(NumericStringT $type): mixed
    {
        return \is_string($this->value) && is_numeric($this->value);
    }

    public function lowercaseString(LowercaseStringT $type): mixed
    {
        return \is_string($this->value) && strtolower($this->value) === $this->value;
    }

    public function stringValue(StringValueT $type): mixed
    {
        return $this->value === $type->value;
    }

    public function scalar(ScalarT $type): mixed
    {
        return \is_scalar($this->value);
    }

    public function numeric(NumericT $type): mixed
    {
        return is_numeric($this->value);
    }

    public function resource(ResourceT $type): mixed
    {
        return \is_resource($this->value);
    }

    public function arrayOpen(ArrayOpenT $type): mixed
    {
        return \is_array($this->value);
    }

    public function callableOpen(CallableOpenT $type): mixed
    {
        return \is_callable($this->value);
    }

    public function iterableOpen(IterableOpenT $type): mixed
    {
        return is_iterable($this->value);
    }

    public function objectOpen(ObjectOpenT $type): mixed
    {
        return \is_object($this->value);
    }

    public function intersection(IntersectionT $type): mixed
    {
        foreach ($type->types as $each) {
            if (!$each->accept($this)) {
                return false;
            }
        }

        return true;
    }

    public function union(UnionT $type): mixed
    {
        foreach ($type->types as $each) {
            if ($each->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function mixed(MixedT $type): mixed
    {
        return true;
    }

    public function fallback(Type $type): mixed
    {
        throw new \RuntimeException(\sprintf('Type `%s` is not supported', stringify($type)));
    }
}
