<?php


declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type;
use Typhoon\Type\ArrayBareT;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableBareT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\ConstantMaskT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableBareT;
use Typhoon\Type\IterableT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectShapeT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

/**
 * @api
 * @template-covariant TResult
 * @implements Visitor<TResult>
 * @codeCoverageIgnore
 */
abstract class Fallback implements Visitor
{
    #[\Override]
    public function neverT(NeverT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function voidT(VoidT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function nullT(NullT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function falseT(FalseT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function trueT(TrueT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function boolT(BoolT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function intT(IntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function intValueT(IntValueT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function intRangeT(IntRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function negativeIntT(NegativeIntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function positiveIntT(PositiveIntT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function bitmaskT(BitmaskT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function floatT(FloatT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function floatRangeT(FloatRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function stringT(StringT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function nonEmptyStringT(NonEmptyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function truthyStringT(TruthyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function numericStringT(NumericStringT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function stringValueT(StringValueT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function classT(ClassT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function listT(ListT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function arrayBareT(ArrayBareT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function arrayT(ArrayT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function objectT(ObjectT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function objectShapeT(ObjectShapeT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function iterableBareT(IterableBareT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function iterableT(IterableT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function callableBareT(CallableBareT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function callableT(CallableT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function closureT(ClosureT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function resourceT(ResourceT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function constantT(ConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function constantMaskT(ConstantMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function classConstantMaskT(ClassConstantMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function intersectionT(IntersectionT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function unionT(UnionT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function numericT(NumericT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function scalarT(ScalarT $type): mixed
    {
        return $this->fallback($type);
    }

    #[\Override]
    public function mixedT(MixedT $type): mixed
    {
        return $this->fallback($type);
    }

    /**
     * @return TResult
     */
    abstract protected function fallback(Type $type): mixed;
}
