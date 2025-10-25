<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayOpenT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableOpenT;
use Typhoon\Type\CallableT;
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
use Typhoon\Type\IterableOpenT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectOpenT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\ParentOpenT;
use Typhoon\Type\ParentT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\SelfOpenT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticOpenT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\SuperClass;
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
    public function bool(BoolT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([FalseT::T, TrueT::T]);

        return $reduced->accept($this);
    }

    public function int(IntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT();

        return $reduced->accept($this);
    }

    public function intValue(IntValueT $type): mixed
    {
        return (new IntRangeT($type->value, $type->value))->accept($this);
    }

    public function negativeInt(NegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: -1);

        return $reduced->accept($this);
    }

    public function nonPositiveInt(NonPositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: 0);

        return $reduced->accept($this);
    }

    public function nonZeroInt(NonZeroIntT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NegativeIntT::T, PositiveIntT::T]);

        return $reduced->accept($this);
    }

    public function nonNegativeInt(NonNegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 0);

        return $reduced->accept($this);
    }

    public function positiveInt(PositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 1);

        return $reduced->accept($this);
    }

    public function float(FloatT $type): mixed
    {
        /** @var FloatRangeT */
        static $reduced = new FloatRangeT();

        return $reduced->accept($this);
    }

    public function floatValue(FloatValueT $type): mixed
    {
        return (new FloatRangeT($type->value, $type->value))->accept($this);
    }

    public function arrayKey(ArrayKeyT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, StringT::T]);

        return $reduced->accept($this);
    }

    public function numeric(NumericT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, FloatT::T, NumericStringT::T]);

        return $reduced->accept($this);
    }

    public function scalar(ScalarT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([BoolT::T, IntT::T, FloatT::T, StringT::T]);

        return $reduced->accept($this);
    }

    public function arrayOpen(ArrayOpenT $type): mixed
    {
        /** @var ArrayT */
        static $reduced = new ArrayT();

        return $reduced->accept($this);
    }

    public function iterableOpen(IterableOpenT $type): mixed
    {
        /** @var IterableT */
        static $reduced = new IterableT();

        return $reduced->accept($this);
    }

    public function objectOpen(ObjectOpenT $type): mixed
    {
        /** @var ObjectT */
        static $reduced = new ObjectT();

        return $reduced->accept($this);
    }

    public function selfOpen(SelfOpenT $type): mixed
    {
        /** @var SelfT */
        static $reduced = new SelfT();

        return $reduced->accept($this);
    }

    public function parentOpen(ParentOpenT $type): mixed
    {
        /** @var ParentT */
        static $reduced = new ParentT();

        return $reduced->accept($this);
    }

    public function staticOpen(StaticOpenT $type): mixed
    {
        /** @var StaticT */
        static $reduced = new StaticT();

        return $reduced->accept($this);
    }

    public function callableOpen(CallableOpenT $type): mixed
    {
        /** @var CallableT */
        static $reduced = new CallableT();

        return $reduced->accept($this);
    }

    public function closure(ClosureT $type): mixed
    {
        return (new IntersectionT([
            new ObjectT(superClasses: [new SuperClass(\Closure::class)]),
            new CallableT($type->templates, $type->parameters, $type->returns),
        ]))->accept($this);
    }

    public function value(ValueT $type): mixed
    {
        return (new OffsetT($type->array, new KeyT($type->array)))->accept($this);
    }

    public function isSupertype(IsSupertypeT $type): mixed
    {
        return (new IsSubtypeT($type->right, $type->left))->accept($this);
    }
}
