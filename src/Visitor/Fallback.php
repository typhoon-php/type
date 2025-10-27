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
use Typhoon\Type\ClassStringT;
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
 * @codeCoverageIgnore
 */
abstract class Fallback extends Reduced
{
    public function neverT(NeverT $type): mixed
    {
        return $this->fallback($type);
    }

    public function voidT(VoidT $type): mixed
    {
        return $this->fallback($type);
    }

    public function nullT(NullT $type): mixed
    {
        return $this->fallback($type);
    }

    public function falseT(FalseT $type): mixed
    {
        return $this->fallback($type);
    }

    public function trueT(TrueT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intRangeT(IntRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intMaskT(IntMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    public function floatRangeT(FloatRangeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function stringT(StringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function nonEmptyStringT(NonEmptyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function truthyStringT(TruthyStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function numericStringT(NumericStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function stringValueT(StringValueT $type): mixed
    {
        return $this->fallback($type);
    }

    public function classStringT(ClassStringT $type): mixed
    {
        return $this->fallback($type);
    }

    public function listT(ListT $type): mixed
    {
        return $this->fallback($type);
    }

    public function arrayT(ArrayT $type): mixed
    {
        return $this->fallback($type);
    }

    public function objectT(ObjectT $type): mixed
    {
        return $this->fallback($type);
    }

    public function selfT(SelfT $type): mixed
    {
        return $this->fallback($type);
    }

    public function parentT(ParentT $type): mixed
    {
        return $this->fallback($type);
    }

    public function staticT(StaticT $type): mixed
    {
        return $this->fallback($type);
    }

    public function iterableT(IterableT $type): mixed
    {
        return $this->fallback($type);
    }

    public function callableT(CallableT $type): mixed
    {
        return $this->fallback($type);
    }

    public function resourceT(ResourceT $type): mixed
    {
        return $this->fallback($type);
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        return $this->fallback($type);
    }

    public function unionT(UnionT $type): mixed
    {
        return $this->fallback($type);
    }

    public function literalT(LiteralT $type): mixed
    {
        return $this->fallback($type);
    }

    public function constantT(ConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    public function classConstantT(ClassConstantT $type): mixed
    {
        return $this->fallback($type);
    }

    public function classConstantMaskT(ClassConstantMaskT $type): mixed
    {
        return $this->fallback($type);
    }

    public function keyT(KeyT $type): mixed
    {
        return $this->fallback($type);
    }

    public function offsetT(OffsetT $type): mixed
    {
        return $this->fallback($type);
    }

    public function isSubtypeT(IsSubtypeT $type): mixed
    {
        return $this->fallback($type);
    }

    public function ternaryT(TernaryT $type): mixed
    {
        return $this->fallback($type);
    }

    public function aliasT(AliasT $type): mixed
    {
        return $this->fallback($type);
    }

    public function templateT(TemplateT $type): mixed
    {
        return $this->fallback($type);
    }

    public function mixedT(MixedT $type): mixed
    {
        return $this->fallback($type);
    }

    /**
     * @return TResult
     */
    abstract protected function fallback(Type $type): mixed;
}
