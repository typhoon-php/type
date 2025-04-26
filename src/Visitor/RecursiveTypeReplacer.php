<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassStringT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\Parameter;
use Typhoon\Type\ParentT;
use Typhoon\Type\Property;
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
 * @api
 * @implements TypeVisitor<Type>
 */
abstract class RecursiveTypeReplacer implements TypeVisitor
{
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

    public function numericString(NumericStringT $type): mixed
    {
        return $type;
    }

    public function lowercaseString(LowercaseStringT $type): mixed
    {
        return $type;
    }

    public function nonEmptyString(NonEmptyStringT $type): mixed
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
        return new UnionT(array_map(
            fn(Type $type): Type => $type->accept($this),
            $type->types,
        ));
    }

    public function intersection(IntersectionT $type): mixed
    {
        return new IntersectionT(array_map(
            fn(Type $type): Type => $type->accept($this),
            $type->types,
        ));
    }

    public function list(ListT $type): mixed
    {
        return new ListT(
            valueType: $type->valueType->accept($this),
            elements: array_map(
                fn(ArrayElement $element): ArrayElement => new ArrayElement(
                    type: $element->type->accept($this),
                    optional: $element->optional,
                ),
                $type->elements,
            ),
        );
    }

    public function array(ArrayT $type): mixed
    {
        return new ArrayT(
            keyType: $type->keyType->accept($this),
            valueType: $type->valueType->accept($this),
            elements: array_map(
                fn(ArrayElement $element): ArrayElement => new ArrayElement(
                    type: $element->type->accept($this),
                    optional: $element->optional,
                ),
                $type->elements,
            ),
        );
    }

    public function classString(ClassStringT $type): mixed
    {
        return new ClassStringT($type->accept($this));
    }

    public function object(ObjectT $type): mixed
    {
        return new ObjectT(
            properties: array_map(
                fn(Property $property): Property => new Property(
                    type: $property->type->accept($this),
                    optional: $property->optional,
                ),
                $type->properties,
            ),
        );
    }

    public function callable(CallableT $type): mixed
    {
        return new CallableT(
            templates: array_map(
                fn(TemplateT $template): TemplateT => new TemplateT(
                    name: $template->name,
                    variance: $template->variance,
                    upperBound: $template->upperBound->accept($this),
                ),
                $type->templates,
            ),
            parameters: array_map(
                fn(Parameter $parameter): Parameter => new Parameter(
                    type: $parameter->type->accept($this),
                    hasDefault: $parameter->hasDefault,
                    variadic: $parameter->variadic,
                    byReference: $parameter->byReference,
                ),
                $type->parameters,
            ),
            returnType: $type->returnType->accept($this),
        );
    }

    public function constant(ConstantT $type): mixed
    {
        return $type;
    }

    public function classConstant(ClassConstantT $type): mixed
    {
        return new ClassConstantT(
            objectType: $type->objectType->accept($this),
            name: $type->name,
        );
    }

    public function classConstantMask(ClassConstantMaskT $type): mixed
    {
        return new ClassConstantMaskT(
            objectType: $type->objectType->accept($this),
            namePrefix: $type->namePrefix,
        );
    }

    public function namedObject(NamedObjectT $type): mixed
    {
        return new NamedObjectT(
            class: $type->class,
            templateArguments: array_map(
                fn(Type $type): Type => $type->accept($this),
                $type->templateArguments,
            ),
        );
    }

    public function alias(AliasT $type): mixed
    {
        return new AliasT(
            classType: $type->classType->accept($this),
            name: $type->name,
            templateArguments: array_map(
                fn(Type $type): Type => $type->accept($this),
                $type->templateArguments,
            ),
        );
    }

    public function self(SelfT $type): mixed
    {
        return new SelfT(
            resolvedObjectType: $type->resolvedObjectType?->accept($this),
            templateArguments: array_map(
                fn(Type $type): Type => $type->accept($this),
                $type->templateArguments,
            ),
        );
    }

    public function parent(ParentT $type): mixed
    {
        return new SelfT(
            resolvedObjectType: $type->resolvedObjectType?->accept($this),
            templateArguments: array_map(
                fn(Type $type): Type => $type->accept($this),
                $type->templateArguments,
            ),
        );
    }

    public function static(StaticT $type): mixed
    {
        return new StaticT(
            resolvedObjectType: $type->resolvedObjectType?->accept($this),
            templateArguments: array_map(
                fn(Type $type): Type => $type->accept($this),
                $type->templateArguments,
            ),
        );
    }

    public function template(TemplateT $type): mixed
    {
        return new TemplateT(
            name: $type->name,
            variance: $type->variance,
            upperBound: $type->upperBound->accept($this),
        );
    }
}
