<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntMaskT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyT;
use Typhoon\Type\ListT;
use Typhoon\Type\LiteralT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\ParentT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\VoidT;

/**
 * @api
 * @template-covariant TResult
 * @extends Reduced<TResult>
 */
abstract class Fallback extends Reduced
{
    public function never(NeverT $type): mixed
    {
        return $this->fallback($type);
    }

    public function void(VoidT $type): mixed
    {
        return $this->fallback($type);
    }

    public function null(NullT $type): mixed
    {
        return $this->fallback($type);
    }

    public function false(FalseT $type): mixed
    {
        return $this->fallback($type);
    }

    public function true(TrueT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intRange(IntRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intMask(IntMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    public function floatRange(FloatRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function string(StringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function nonEmptyString(NonEmptyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function truthyString(TruthyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function numericString(NumericStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function lowercaseString(LowercaseStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function stringValue(StringValueT $type): mixed
    {
        return $this->fallback($type);
    }

    public function class(ClassT $type): mixed
    {
        return $this->fallback($type);
    }

    public function list(ListT $type): mixed
    {
        return $this->fallback($type);
    }

    public function array(ArrayT $type): mixed
    {
        return $this->fallback($type);
    }

    public function iterable(IterableT $type): mixed
    {
        return $this->fallback($type);
    }

    public function object(ObjectT $type): mixed
    {
        return $this->fallback($type);
    }

    public function self(SelfT $type): mixed
    {
        return $this->fallback($type);
    }

    public function parent(ParentT $type): mixed
    {
        return $this->fallback($type);
    }

    public function static(StaticT $type): mixed
    {
        return $this->fallback($type);
    }

    public function callable(CallableT $type): mixed
    {
        return $this->fallback($type);
    }

    public function resource(ResourceT $type): mixed
    {
        return $this->fallback($type);
    }

    public function literal(LiteralT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intersection(IntersectionT $type): mixed
    {
        return $this->fallback($type);
    }

    public function union(UnionT $type): mixed
    {
        return $this->fallback($type);
    }

    public function constant(ConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    public function classConstant(ClassConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    public function classConstantMask(ClassConstantMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    public function key(KeyT $type): mixed
    {
        return $this->fallback($type);
    }

    public function offset(OffsetT $type): mixed
    {
        return $this->fallback($type);
    }

    public function isSubtype(IsSubtypeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function ternary(TernaryT $type): mixed
    {
        return $this->fallback($type);
    }

    public function alias(AliasT $type): mixed
    {
        return $this->fallback($type);
    }

    public function mixed(MixedT $type): mixed
    {
        return $this->fallback($type);
    }

    public function template(TemplateT $type): mixed
    {
        return $this->fallback($type);
    }

    /**
     * @return TResult
     */
    abstract protected function fallback(Type $type): mixed;
}
