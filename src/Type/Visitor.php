<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant TResult
 */
interface Visitor
{
    /**
     * @return TResult
     */
    public function neverT(NeverT $type): mixed;

    /**
     * @return TResult
     */
    public function voidT(VoidT $type): mixed;

    /**
     * @return TResult
     */
    public function nullT(NullT $type): mixed;

    /**
     * @return TResult
     */
    public function falseT(FalseT $type): mixed;

    /**
     * @return TResult
     */
    public function trueT(TrueT $type): mixed;

    /**
     * @return TResult
     */
    public function boolT(BoolT $type): mixed;

    /**
     * @return TResult
     */
    public function intT(IntT $type): mixed;

    /**
     * @return TResult
     */
    public function intValueT(IntValueT $type): mixed;

    /**
     * @return TResult
     */
    public function intRangeT(IntRangeT $type): mixed;

    /**
     * @return TResult
     */
    public function negativeIntT(NegativeIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonPositiveIntT(NonPositiveIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonZeroIntT(NonZeroIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonNegativeIntT(NonNegativeIntT $type): mixed;

    /**
     * @return TResult
     */
    public function positiveIntT(PositiveIntT $type): mixed;

    /**
     * @return TResult
     */
    public function bitmaskT(BitmaskT $type): mixed;

    /**
     * @return TResult
     */
    public function floatT(FloatT $type): mixed;

    /**
     * @return TResult
     */
    public function floatValueT(FloatValueT $type): mixed;

    /**
     * @return TResult
     */
    public function floatRangeT(FloatRangeT $type): mixed;

    /**
     * @return TResult
     */
    public function stringT(StringT $type): mixed;

    /**
     * @return TResult
     */
    public function nonEmptyStringT(NonEmptyStringT $type): mixed;

    /**
     * @return TResult
     */
    public function truthyStringT(TruthyStringT $type): mixed;

    /**
     * @return TResult
     */
    public function numericStringT(NumericStringT $type): mixed;

    /**
     * @return TResult
     */
    public function lowercaseStringT(LowercaseStringT $type): mixed;

    /**
     * @return TResult
     */
    public function stringValueT(StringValueT $type): mixed;

    /**
     * @return TResult
     */
    public function classT(ClassT $type): mixed;

    /**
     * @return TResult
     */
    public function listT(ListT $type): mixed;

    /**
     * @return TResult
     */
    public function arrayBareT(ArrayBareT $type): mixed;

    /**
     * @return TResult
     */
    public function arrayT(ArrayT $type): mixed;

    /**
     * @return TResult
     */
    public function objectT(ObjectT $type): mixed;

    /**
     * @return TResult
     */
    public function namedObjectT(NamedObjectT $type): mixed;

    /**
     * @return TResult
     */
    public function objectShapeT(ObjectShapeT $type): mixed;

    /**
     * @return TResult
     */
    public function iterableBareT(IterableBareT $type): mixed;

    /**
     * @return TResult
     */
    public function iterableT(IterableT $type): mixed;

    /**
     * @return TResult
     */
    public function callableBareT(CallableBareT $type): mixed;

    /**
     * @return TResult
     */
    public function callableT(CallableT $type): mixed;

    /**
     * @return TResult
     */
    public function closureT(ClosureT $type): mixed;

    /**
     * @return TResult
     */
    public function resourceT(ResourceT $type): mixed;

    /**
     * @return TResult
     */
    public function constantT(ConstantT $type): mixed;

    /**
     * @return TResult
     */
    public function constantMaskT(ConstantMaskT $type): mixed;

    /**
     * @return TResult
     */
    public function classConstantT(ClassConstantT $type): mixed;

    /**
     * @return TResult
     */
    public function classConstantMaskT(ClassConstantMaskT $type): mixed;

    /**
     * @return TResult
     */
    public function intersectionT(IntersectionT $type): mixed;

    /**
     * @return TResult
     */
    public function unionT(UnionT $type): mixed;

    /**
     * @return TResult
     */
    public function arrayKeyT(ArrayKeyT $type): mixed;

    /**
     * @return TResult
     */
    public function numericT(NumericT $type): mixed;

    /**
     * @return TResult
     */
    public function scalarT(ScalarT $type): mixed;

    /**
     * @return TResult
     */
    public function mixedT(MixedT $type): mixed;
}
