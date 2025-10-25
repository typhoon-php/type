<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\TypeGenerator\Generator;

if (class_exists(Generator::class, autoload: false)) {
    return;
}

const neverT = NeverT::T;

const voidT = VoidT::T;

const nullT = NullT::T;

const falseT = FalseT::T;

const trueT = TrueT::T;

const boolT = BoolT::T;

const intT = IntT::T;

/**
 * @api
 * @param int|numeric-string $value
 */
function intT(int|string $value): IntRangeT
{
    return new IntRangeT($value, $value);
}

/**
 * @api
 * @param null|int|numeric-string $min
 * @param null|int|numeric-string $max
 */
function intRangeT(null|int|string $min = null, null|int|string $max = null): IntRangeT
{
    return new IntRangeT(min: $min, max: $max);
}

const negativeIntT = NegativeIntT::T;

const nonPositiveIntT = NonPositiveIntT::T;

const nonNegativeIntT = NonNegativeIntT::T;

const positiveIntT = PositiveIntT::T;

/**
 * @api
 * @no-named-arguments
 * @param positive-int $value
 * @param positive-int ...$values
 */
function intMaskT(int $value, int ...$values): IntMaskOfT
{
    return new IntMaskOfT(new UnionT(array_map(intT(...), [$value, ...$values])));
}

/**
 * @api
 */
function intMaskOfT(Type $type): IntMaskOfT
{
    return new IntMaskOfT($type);
}

const floatT = FloatT::T;

/**
 * @api
 * @param null|int|float|numeric-string $value
 */
function floatT(null|int|float|string $value): FloatRangeT
{
    return new FloatRangeT($value, $value);
}

/**
 * @api
 * @param null|int|float|numeric-string $min
 * @param null|int|float|numeric-string $max
 */
function floatRangeT(null|int|float|string $min = null, null|int|float|string $max = null): FloatRangeT
{
    return new FloatRangeT($min, $max);
}

const stringT = StringT::T;

const nonEmptyStringT = NonEmptyStringT::T;

const numericStringT = NumericStringT::T;

const lowercaseStringT = LowercaseStringT::T;

/**
 * @api
 */
function stringT(string $value): StringValueT
{
    return new StringValueT($value);
}

/**
 * @api
 */
function classStringT(Type $of): ClassStringT
{
    return new ClassStringT($of);
}

/**
 * @api
 * @param class-string $class
 */
function classT(string $class): ClassConstantT
{
    return new ClassConstantT(objectT($class), 'class');
}

const arrayKeyT = ArrayKeyT::T;

const arrayT = NativeArrayT::T;

/**
 * @api
 */
function arrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT(key: $key, value: $value);
}

/**
 * @api
 */
function nonEmptyArrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT(key: $key, value: $value, isNonEmpty: true);
}

/**
 * @api
 * @param array<ArrayElement|Type> $elements
 */
function arrayShapeT(array $elements = []): ArrayT
{
    return unsealedArrayShapeT($elements, neverT, neverT);
}

/**
 * @api
 * @param array<ArrayElement|Type> $elements
 */
function unsealedArrayShapeT(array $elements = [], Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT(
        key: $key,
        value: $value,
        elements: array_map(
            static fn(ArrayElement|Type $e) => $e instanceof ArrayElement ? $e : new ArrayElement($e),
            $elements,
        ),
    );
}

/**
 * @api
 */
function keyOfT(Type $of): KeyOfT
{
    return new KeyOfT($of);
}

/**
 * @api
 */
function valueOfT(Type $of): OffsetT
{
    return offsetT($of, keyOfT($of));
}

/**
 * @api
 */
function offsetT(Type $value, Type $key): OffsetT
{
    return new OffsetT($value, $key);
}

const iterableT = NativeIterableT::T;

/**
 * @api
 */
function iterableT(Type $key = mixedT, Type $value = mixedT): IterableT
{
    return new IterableT($key, $value);
}

const objectT = NativeObjectT::T;

/**
 * @api
 * @param class-string $class
 * @param list<Type> $templateArguments
 */
function objectT(string $class, array $templateArguments = []): ObjectT
{
    return new ObjectT(superClasses: [new SuperClass($class, $templateArguments)]);
}

const resourceT = ResourceT::T;

const numericT = NumericT::T;

const scalarT = ScalarT::T;

const mixedT = MixedT::T;

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function unionT(Type|array $types, Type ...$moreTypes): Type
{
    $types = [...(\is_array($types) ? $types : [$types]), ...$moreTypes];

    return match (\count($types)) {
        0 => NeverT::T,
        1 => $types[0],
        default => new UnionT($types),
    };
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function orT(Type|array $types, Type ...$moreTypes): Type
{
    return unionT($types, ...$moreTypes);
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function intersectionT(Type|array $types, Type ...$moreTypes): Type
{
    $types = [...(\is_array($types) ? $types : [$types]), ...$moreTypes];

    return match (\count($types)) {
        0 => mixedT,
        1 => $types[0],
        default => new IntersectionT($types),
    };
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function andT(Type|array $types, Type ...$moreTypes): Type
{
    return intersectionT($types, ...$moreTypes);
}

/**
 * @api
 * @template T
 * @param Type<T> $type
 * @return Type<null|T>
 */
function nullOrT(Type $type): Type
{
    return new UnionT([NullT::T, $type]);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Invariant>
 */
function template(string $name, Type $upperBound = MixedT::T, Type $lowerBound = NeverT::T): Template
{
    return new Template(
        name: $name,
        lowerBound: $lowerBound,
        upperBound: $upperBound,
        variance: Variance::Invariant,
    );
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Covariant>
 */
function covariantTemplate(string $name, Type $upperBound = MixedT::T, Type $lowerBound = NeverT::T): Template
{
    return new Template(
        name: $name,
        lowerBound: $lowerBound,
        upperBound: $upperBound,
        variance: Variance::Covariant,
    );
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Contravariant>
 */
function contravariantTemplate(string $name, Type $upperBound = MixedT::T, Type $lowerBound = NeverT::T): Template
{
    return new Template(
        name: $name,
        lowerBound: $lowerBound,
        upperBound: $upperBound,
        variance: Variance::Contravariant,
    );
}

function param(Type $type, bool $hasDefault = false): Parameter
{
    return new Parameter($type, $hasDefault);
}

/**
 * @param list<Template<Variance::Invariant>> $templates
 * @param list<Parameter|Type> $parameters
 */
function callableT(array $templates = [], array $parameters = [], Type $returns = mixedT): CallableT
{
    return new CallableT(
        templates: $templates,
        parameters: array_map(
            static fn(Parameter|Type $p): Parameter => $p instanceof Parameter ? $p : new Parameter($p),
            $parameters,
        ),
        returns: $returns,
    );
}

function of(mixed $value): Type
{
    /** @phpstan-ignore match.unhandled */
    return match (true) {
        $value === null => nullT,
        $value === false => falseT,
        $value === true => trueT,
        \is_int($value) => intT($value),
        \is_float($value) => floatT($value),
        \is_string($value) => stringT($value),
        \is_array($value) => arrayShapeT(array_map(of(...), $value)),
        \is_object($value) => objectT($value::class),
        \is_resource($value) => resourceT,
    };
}
