<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
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
    public function intRange(IntRangeT $type): mixed;

    /**
     * @return TResult
     */
    public function intMaskOf(IntMaskOfT $type): mixed;

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
    public function classString(ClassStringT $type): mixed;

    /**
     * @return TResult
     */
    public function list(ListT $type): mixed;

    /**
     * @return TResult
     */
    public function array(ArrayT $type): mixed;

    /**
     * @return TResult
     */
    public function keyOf(KeyOfT $type): mixed;

    /**
     * @return TResult
     */
    public function offset(OffsetT $type): mixed;

    /**
     * @return TResult
     */
    public function object(ObjectT $type): mixed;

    /**
     * @return TResult
     */
    public function self(SelfT $type): mixed;

    /**
     * @return TResult
     */
    public function parent(ParentT $type): mixed;

    /**
     * @return TResult
     */
    public function static(StaticT $type): mixed;

    /**
     * @return TResult
     */
    public function callable(CallableT $type): mixed;

    /**
     * @return TResult
     */
    public function resource(ResourceT $type): mixed;

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
    public function template(TemplateT $type): mixed;

    /**
     * @return TResult
     */
    public function alias(AliasT $type): mixed;

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
    public function isSubtype(IsSubtypeT $type): mixed;

    /**
     * @return TResult
     */
    public function ternary(TernaryT $type): mixed;

    /**
     * @return TResult
     */
    public function shortcut(Shortcut $type): mixed;
}
