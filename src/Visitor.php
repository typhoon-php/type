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
    public function never(NeverT $type): mixed;

    /**
     * @return TResult
     */
    public function void(VoidT $type): mixed;

    /**
     * @return TResult
     */
    public function null(NullT $type): mixed;

    /**
     * @return TResult
     */
    public function false(FalseT $type): mixed;

    /**
     * @return TResult
     */
    public function true(TrueT $type): mixed;

    /**
     * @return TResult
     */
    public function bool(BoolT $type): mixed;

    /**
     * @return TResult
     */
    public function int(IntT $type): mixed;

    /**
     * @return TResult
     */
    public function intValue(IntValueT $type): mixed;

    /**
     * @return TResult
     */
    public function intRange(IntRangeT $type): mixed;

    /**
     * @return TResult
     */
    public function negativeInt(NegativeIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonPositiveInt(NonPositiveIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonZeroInt(NonZeroIntT $type): mixed;

    /**
     * @return TResult
     */
    public function nonNegativeInt(NonNegativeIntT $type): mixed;

    /**
     * @return TResult
     */
    public function positiveInt(PositiveIntT $type): mixed;

    /**
     * @return TResult
     */
    public function intMask(IntMaskT $type): mixed;

    /**
     * @return TResult
     */
    public function float(FloatT $type): mixed;

    /**
     * @return TResult
     */
    public function floatValue(FloatValueT $type): mixed;

    /**
     * @return TResult
     */
    public function floatRange(FloatRangeT $type): mixed;

    /**
     * @return TResult
     */
    public function string(StringT $type): mixed;

    /**
     * @return TResult
     */
    public function nonEmptyString(NonEmptyStringT $type): mixed;

    /**
     * @return TResult
     */
    public function truthyString(TruthyStringT $type): mixed;

    /**
     * @return TResult
     */
    public function numericString(NumericStringT $type): mixed;

    /**
     * @return TResult
     */
    public function lowercaseString(LowercaseStringT $type): mixed;

    /**
     * @return TResult
     */
    public function stringValue(StringValueT $type): mixed;

    /**
     * @return TResult
     */
    public function class(ClassT $type): mixed;

    /**
     * @return TResult
     */
    public function arrayKey(ArrayKeyT $type): mixed;

    /**
     * @return TResult
     */
    public function numeric(NumericT $type): mixed;

    /**
     * @return TResult
     */
    public function scalar(ScalarT $type): mixed;

    /**
     * @return TResult
     */
    public function list(ListT $type): mixed;

    /**
     * @return TResult
     */
    public function arrayOpen(ArrayOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function array(ArrayT $type): mixed;

    /**
     * @return TResult
     */
    public function iterableOpen(IterableOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function iterable(IterableT $type): mixed;

    /**
     * @return TResult
     */
    public function objectOpen(ObjectOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function object(ObjectT $type): mixed;

    /**
     * @return TResult
     */
    public function selfOpen(SelfOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function self(SelfT $type): mixed;

    /**
     * @return TResult
     */
    public function parentOpen(ParentOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function parent(ParentT $type): mixed;

    /**
     * @return TResult
     */
    public function staticOpen(StaticOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function static(StaticT $type): mixed;

    /**
     * @return TResult
     */
    public function callableOpen(CallableOpenT $type): mixed;

    /**
     * @return TResult
     */
    public function callable(CallableT $type): mixed;

    /**
     * @return TResult
     */
    public function closure(ClosureT $type): mixed;

    /**
     * @return TResult
     */
    public function resource(ResourceT $type): mixed;

    /**
     * @return TResult
     */
    public function literal(LiteralT $type): mixed;

    /**
     * @return TResult
     */
    public function intersection(IntersectionT $type): mixed;

    /**
     * @return TResult
     */
    public function union(UnionT $type): mixed;

    /**
     * @return TResult
     */
    public function constant(ConstantT $type): mixed;

    /**
     * @return TResult
     */
    public function classConstant(ClassConstantT $type): mixed;

    /**
     * @return TResult
     */
    public function classConstantMask(ClassConstantMaskT $type): mixed;

    /**
     * @return TResult
     */
    public function key(KeyT $type): mixed;

    /**
     * @return TResult
     */
    public function value(ValueT $type): mixed;

    /**
     * @return TResult
     */
    public function offset(OffsetT $type): mixed;

    /**
     * @return TResult
     */
    public function isSubtype(IsSubtypeT $type): mixed;

    /**
     * @return TResult
     */
    public function isSupertype(IsSupertypeT $type): mixed;

    /**
     * @return TResult
     */
    public function ternary(TernaryT $type): mixed;

    /**
     * @return TResult
     */
    public function alias(AliasT $type): mixed;

    /**
     * @return TResult
     */
    public function mixed(MixedT $type): mixed;

    /**
     * @return TResult
     */
    public function template(TemplateT $type): mixed;
}
