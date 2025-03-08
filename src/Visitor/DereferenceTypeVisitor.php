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
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NullT;
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
 * @implements TypeVisitor<Type>
 */
enum DereferenceTypeVisitor implements TypeVisitor
{
    case Visitor;

    public function never(NeverT $type): mixed
    {
        return $type;
    }

    public function void(VoidT $type): mixed
    {
        return $type;
    }

    public function null(NullT $type): mixed
    {
        return $type;
    }

    public function false(FalseT $type): mixed
    {
        return $type;
    }

    public function true(TrueT $type): mixed
    {
        return $type;
    }

    public function intRange(IntRangeT $type): mixed
    {
        return $type;
    }

    public function floatRange(FloatRangeT $type): mixed
    {
        return $type;
    }

    public function stringValue(StringValueT $type): mixed
    {
        return $type;
    }

    public function string(StringT $type): mixed
    {
        return $type;
    }

    public function resource(ResourceT $type): mixed
    {
        return $type;
    }

    public function union(UnionT $type): mixed
    {
        return $type;
    }

    public function intersection(IntersectionT $type): mixed
    {
        return $type;
    }

    public function diff(DiffT $type): mixed
    {
        return $type;
    }

    public function list(ListT $type): mixed
    {
        return $type;
    }

    public function array(ArrayT $type): mixed
    {
        return $type;
    }

    public function classString(ClassStringT $type): mixed
    {
        return $type;
    }

    public function object(ObjectT $type): mixed
    {
        return $type;
    }

    public function callable(CallableT $type): mixed
    {
        return $type;
    }

    public function constant(ConstantT $type): mixed
    {
        return $type;
    }

    public function classConstant(ClassConstantT $type): mixed
    {
        return $type;
    }

    public function classConstantMask(ClassConstantMaskT $type): mixed
    {
        return $type;
    }

    public function namedObject(NamedObjectT $type): mixed
    {
        return $type;
    }

    public function alias(AliasT $type): mixed
    {
        return $type;
    }

    public function self(SelfT $type): mixed
    {
        return $type;
    }

    public function parent(ParentT $type): mixed
    {
        return $type;
    }

    public function static(StaticT $type): mixed
    {
        return $type;
    }

    public function template(TemplateT $type): mixed
    {
        return $type;
    }
}
