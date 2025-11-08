<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasAtClassT;
use Typhoon\Type\AliasAtFunctionT;
use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayBareT;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BoolT;
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
use Typhoon\Type\IterableBareT;
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
use Typhoon\Type\ObjectBareT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\TrueT;
use Typhoon\Type\UnionT;
use Typhoon\Type\UntypedT;
use Typhoon\Type\ValueOfT;

/**
 * @api
 * @codeCoverageIgnore
 */
trait Reduced
{
    #[\Override]
    public function boolT(BoolT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([FalseT::T, TrueT::T]);

        return $this->unionT($reduced);
    }

    #[\Override]
    public function intT(IntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT();

        return $this->intRangeT($reduced);
    }

    #[\Override]
    public function intValueT(IntValueT $type): mixed
    {
        return $this->intRangeT(new IntRangeT($type->value, $type->value));
    }

    #[\Override]
    public function negativeIntT(NegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: -1);

        return $this->intRangeT($reduced);
    }

    #[\Override]
    public function nonPositiveIntT(NonPositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(max: 0);

        return $this->intRangeT($reduced);
    }

    #[\Override]
    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NegativeIntT::T, PositiveIntT::T]);

        return $this->unionT($reduced);
    }

    #[\Override]
    public function nonNegativeIntT(NonNegativeIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 0);

        return $this->intRangeT($reduced);
    }

    #[\Override]
    public function positiveIntT(PositiveIntT $type): mixed
    {
        /** @var IntRangeT */
        static $reduced = new IntRangeT(min: 1);

        return $this->intRangeT($reduced);
    }

    #[\Override]
    public function floatT(FloatT $type): mixed
    {
        /** @var FloatRangeT */
        static $reduced = new FloatRangeT();

        return $this->floatRangeT($reduced);
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): mixed
    {
        return $this->floatRangeT(new FloatRangeT($type->value, $type->value));
    }

    #[\Override]
    public function arrayKeyT(ArrayKeyT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, StringT::T]);

        return $this->unionT($reduced);
    }

    #[\Override]
    public function numericT(NumericT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([IntT::T, FloatT::T, NumericStringT::T]);

        return $this->unionT($reduced);
    }

    #[\Override]
    public function scalarT(ScalarT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([BoolT::T, IntT::T, FloatT::T, StringT::T]);

        return $this->unionT($reduced);
    }

    #[\Override]
    public function arrayBareT(ArrayBareT $type): mixed
    {
        /** @var ArrayT */
        static $reduced = new ArrayT();

        return $this->arrayT($reduced);
    }

    #[\Override]
    public function objectBareT(ObjectBareT $type): mixed
    {
        /** @var ObjectT */
        static $reduced = new ObjectT();

        return $this->objectT($reduced);
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): mixed
    {
        return $this->objectT(new ObjectT(supertypes: [$type]));
    }

    #[\Override]
    public function iterableBareT(IterableBareT $type): mixed
    {
        /** @var IterableT */
        static $reduced = new IterableT();

        return $this->iterableT($reduced);
    }

    #[\Override]
    public function closureT(ClosureT $type): mixed
    {
        return $this->intersectionT(new IntersectionT([
            new NamedObjectT(\Closure::class),
            new CallableT($type->templates, $type->parameters, $type->returnType),
        ]));
    }

    #[\Override]
    public function valueOfT(ValueOfT $type): mixed
    {
        return $this->offsetT(new OffsetT($type->arrayType, new KeyOfT($type->arrayType)));
    }

    #[\Override]
    public function aliasAtFunctionT(AliasAtFunctionT $type): mixed
    {
        return (new AliasT($type))->accept($this);
    }

    #[\Override]
    public function aliasAtClassT(AliasAtClassT $type): mixed
    {
        return (new AliasT($type))->accept($this);
    }

    #[\Override]
    public function untypedT(UntypedT $type): mixed
    {
        return $this->mixedT(MixedT::T);
    }

    #[\Override]
    public function mixedT(MixedT $type): mixed
    {
        /** @var UnionT */
        static $reduced = new UnionT([NullT::T, ScalarT::T, ArrayBareT::T, ObjectBareT::T, ResourceT::T]);

        return $this->unionT($reduced);
    }
}
