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
use Typhoon\Type\ClosureT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableDefaultT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyOfT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectDefaultT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueOfT;
use Typhoon\Type\Visitor;

/**
 * @api
 * @template-covariant TResult
 * @implements Visitor<TResult>
 * @codeCoverageIgnore
 */
abstract class Reduced implements Visitor
{
    #[\Override]
    public function boolT(BoolT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([FalseT::T, TrueT::T]);

        return $reduced->accept($this);
    }

    #[\Override]
    public function intT(IntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function intValueT(IntValueT $type): mixed
    {
        return (new IntRangeT($type->value, $type->value))->accept($this);
    }

    #[\Override]
    public function negativeIntT(NegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: -1);

        return $reduced->accept($this);
    }

    #[\Override]
    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: 0);

        return $reduced->accept($this);
    }

    #[\Override]
    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NegativeIntT::T, PositiveIntT::T]);

        return $reduced->accept($this);
    }

    #[\Override]
    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 0);

        return $reduced->accept($this);
    }

    #[\Override]
    public function positiveIntT(PositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 1);

        return $reduced->accept($this);
    }

    #[\Override]
    public function floatT(FloatT $type): mixed
    {
        /** @var FloatRangeT */
        static $reduced = new FloatRangeT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): mixed
    {
        return (new FloatRangeT($type->value, $type->value))->accept($this);
    }

    #[\Override]
    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, StringT::T]);

        return $reduced->accept($this);
    }

    #[\Override]
    public function numericT(NumericT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, FloatT::T, NumericStringT::T]);

        return $reduced->accept($this);
    }

    #[\Override]
    public function scalarT(ScalarT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([BoolT::T, IntT::T, FloatT::T, StringT::T]);

        return $reduced->accept($this);
    }

    #[\Override]
    public function arrayDefaultT(ArrayDefaultT $type): mixed
    {
        /** @var ArrayT */
        static $reduced = new ArrayT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function objectDefaultT(ObjectDefaultT $type): mixed
    {
        /** @var ObjectT */
        static $reduced = new ObjectT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): mixed
    {
        return (new ObjectT(supertypes: [$type]))->accept($this);
    }

    #[\Override]
    public function iterableDefaultT(IterableDefaultT $type): mixed
    {
        /** @var IterableT */
        static $reduced = new IterableT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function callableDefaultT(CallableDefaultT $type): mixed
    {
        /** @var CallableT */
        static $reduced = new CallableT();

        return $reduced->accept($this);
    }

    #[\Override]
    public function closureT(ClosureT $type): mixed
    {
        return (new IntersectionT([
            new NamedObjectT(\Closure::class),
            new CallableT($type->templates, $type->parameters, $type->returnType),
        ]))->accept($this);
    }

    #[\Override]
    public function valueOfT(ValueOfT $type): mixed
    {
        return (new OffsetT($type->arrayType, new KeyOfT($type->arrayType)))->accept($this);
    }

    #[\Override]
    public function mixedT(MixedT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NullT::T, ScalarT::T, ArrayDefaultT::T, ObjectDefaultT::T, ResourceT::T]);

        return $reduced->accept($this);
    }

    /**
     * @return TResult
     */
    final public function visit(Type $type): mixed
    {
        return $type->accept($this);
    }

    /**
     * @param list<Type> $types
     * @return ($types is non-empty-list ? non-empty-list<TResult> : list<TResult>)
     */
    final public function visitMultiple(array $types): array
    {
        return array_map($this->visit(...), $types);
    }
}
