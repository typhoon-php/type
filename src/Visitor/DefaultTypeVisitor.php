<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassStringT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\DiffT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\ParentT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\UnionT;
use Typhoon\Type\VoidT;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @template-covariant TResult
 * @implements TypeVisitor<TResult>
 */
abstract class DefaultTypeVisitor implements TypeVisitor
{
    public function never(NeverT $type): mixed
    {
        return $this->default($type);
    }

    public function void(VoidT $type): mixed
    {
        return $this->default($type);
    }

    public function null(NullT $type): mixed
    {
        return $this->default($type);
    }

    public function false(FalseT $type): mixed
    {
        return $this->default($type);
    }

    public function true(TrueT $type): mixed
    {
        return $this->default($type);
    }

    public function intRange(IntRangeT $type): mixed
    {
        return $this->default($type);
    }

    public function floatRange(FloatRangeT $type): mixed
    {
        return $this->default($type);
    }

    public function stringValue(StringValueT $type): mixed
    {
        return $this->default($type);
    }

    public function numericString(NumericStringT $type): mixed
    {
        return $this->default($type);
    }

    public function lowercaseString(LowercaseStringT $type): mixed
    {
        return $this->default($type);
    }

    public function string(StringT $type): mixed
    {
        return $this->default($type);
    }

    public function resource(ResourceT $type): mixed
    {
        return $this->default($type);
    }

    public function list(ListT $type): mixed
    {
        return $this->default($type);
    }

    public function array(ArrayT $type): mixed
    {
        return $this->default($type);
    }

    public function classString(ClassStringT $type): mixed
    {
        return $this->default($type);
    }

    public function object(ObjectT $type): mixed
    {
        return $this->default($type);
    }

    public function callable(CallableT $type): mixed
    {
        return $this->default($type);
    }

    public function template(TemplateT $type): mixed
    {
        return $this->default($type);
    }

    public function diff(DiffT $type): mixed
    {
        return $this->default($type);
    }

    public function intersection(IntersectionT $type): mixed
    {
        return $this->default($type);
    }

    public function union(UnionT $type): mixed
    {
        return $this->default($type);
    }

    public function constant(ConstantT $type): mixed
    {
        return $this->default($type);
    }

    public function classConstant(ClassConstantT $type): mixed
    {
        return $this->default($type);
    }

    public function classConstantMask(ClassConstantMaskT $type): mixed
    {
        return $this->default($type);
    }

    public function namedObject(NamedObjectT $type): mixed
    {
        return $this->default($type);
    }

    public function alias(AliasT $type): mixed
    {
        return $this->default($type);
    }

    public function self(SelfT $type): mixed
    {
        return $this->default($type);
    }

    public function parent(ParentT $type): mixed
    {
        return $this->default($type);
    }

    public function static(StaticT $type): mixed
    {
        return $this->default($type);
    }

    /**
     * @return TResult
     */
    abstract public function default(Type $type): mixed;
}
