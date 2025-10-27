<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\ArrayDefaultT;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableDefaultT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClosureDefaultT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IsSupertypeT;
use Typhoon\Type\IterableDefaultT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectDefaultT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\ParentDefaultT;
use Typhoon\Type\ParentT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\SelfDefaultT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticDefaultT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\TrueT;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueT;
use Typhoon\Type\Visitor;

/**
 * @api
 * @template-covariant TResult
 * @implements Visitor<TResult>
 */
abstract class Reduced implements Visitor
{
    public function boolT(BoolT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([FalseT::T, TrueT::T]);

        return $reduced->accept($this);
    }

    public function intT(IntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT();

        return $reduced->accept($this);
    }

    public function intValueT(IntValueT $type): mixed
    {
        return (new IntRangeT($type->value, $type->value))->accept($this);
    }

    public function negativeIntT(NegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: -1);

        return $reduced->accept($this);
    }

    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: 0);

        return $reduced->accept($this);
    }

    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NegativeIntT::T, PositiveIntT::T]);

        return $reduced->accept($this);
    }

    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 0);

        return $reduced->accept($this);
    }

    public function positiveIntT(PositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 1);

        return $reduced->accept($this);
    }

    public function floatT(FloatT $type): mixed
    {
        /** @var FloatRangeT */
        static $reduced = new FloatRangeT();

        return $reduced->accept($this);
    }

    public function floatValueT(FloatValueT $type): mixed
    {
        return (new FloatRangeT($type->value, $type->value))->accept($this);
    }

    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, StringT::T]);

        return $reduced->accept($this);
    }

    public function numericT(NumericT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, FloatT::T, NumericStringT::T]);

        return $reduced->accept($this);
    }

    public function scalarT(ScalarT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([BoolT::T, IntT::T, FloatT::T, StringT::T]);

        return $reduced->accept($this);
    }

    public function arrayDefaultT(ArrayDefaultT $type): mixed
    {
        /** @var ArrayT */
        static $reduced = new ArrayT();

        return $reduced->accept($this);
    }

    public function objectDefaultT(ObjectDefaultT $type): mixed
    {
        /** @var ObjectT */
        static $reduced = new ObjectT();

        return $reduced->accept($this);
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        return (new ObjectT(superTypes: [$type]))->accept($this);
    }

    public function selfDefaultT(SelfDefaultT $type): mixed
    {
        /** @var SelfT */
        static $reduced = new SelfT();

        return $reduced->accept($this);
    }

    public function parentDefaultT(ParentDefaultT $type): mixed
    {
        /** @var ParentT */
        static $reduced = new ParentT();

        return $reduced->accept($this);
    }

    public function staticDefaultT(StaticDefaultT $type): mixed
    {
        /** @var StaticT */
        static $reduced = new StaticT();

        return $reduced->accept($this);
    }

    public function iterableDefaultT(IterableDefaultT $type): mixed
    {
        /** @var IterableT */
        static $reduced = new IterableT();

        return $reduced->accept($this);
    }

    public function callableDefaultT(CallableDefaultT $type): mixed
    {
        /** @var CallableT */
        static $reduced = new CallableT();

        return $reduced->accept($this);
    }

    public function closureDefaultT(ClosureDefaultT $type): mixed
    {
        /** @var NamedObjectT */
        static $reduced = new NamedObjectT(\Closure::class);

        return $reduced->accept($this);
    }

    public function closureT(ClosureT $type): mixed
    {
        return (new IntersectionT([
            ClosureDefaultT::T,
            new CallableT($type->templates, $type->parameters, $type->returns),
        ]))->accept($this);
    }

    public function valueT(ValueT $type): mixed
    {
        return (new OffsetT($type->array, new KeyT($type->array)))->accept($this);
    }

    public function isSupertypeT(IsSupertypeT $type): mixed
    {
        return (new IsSubtypeT($type->right, $type->left))->accept($this);
    }
}
