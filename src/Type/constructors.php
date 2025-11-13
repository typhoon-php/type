<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Brick\Math\BigDecimal;
use Typhoon\Type;
use Typhoon\Type\Generator\Generator;
use Typhoon\Type\Internal\Optional;

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
 * @template T of int
 * @param T $value
 * @return IntValueT<T>
 */
function intT(int $value): IntValueT
{
    return new IntValueT($value);
}

/**
 * @api
 * @return IntRangeT<int>
 */
function intRangeT(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): IntRangeT
{
    return new IntRangeT($min, $max);
}

const negativeIntT = NegativeIntT::T;

const nonPositiveIntT = NonPositiveIntT::T;

const nonZeroIntT = NonZeroIntT::T;

const nonNegativeIntT = NonNegativeIntT::T;

const positiveIntT = PositiveIntT::T;

/**
 * @api
 * @no-named-arguments
 * @param int|Type|non-empty-list<int|Type> $ints
 * @return BitmaskT<int>
 */
function bitmaskT(int|Type|array $ints, int|Type ...$moreInts): BitmaskT
{
    return new BitmaskT(orT(array_map(
        static fn(int|Type $int): Type => \is_int($int) ? intT($int) : $int,
        [...(\is_array($ints) ? $ints : [$ints]), ...$moreInts],
    )));
}

/**
 * @api
 * @no-named-arguments
 * @param int|Type|non-empty-list<int|Type> $ints
 * @return BitmaskT<int>
 */
function intMaskT(int|Type|array $ints, int|Type ...$moreInts): BitmaskT
{
    return bitmaskT($ints, ...$moreInts);
}

const floatT = FloatT::T;

/**
 * @api
 * @param float|numeric-string|BigDecimal $value
 * @return FloatValueT<float>
 */
function floatT(float|string|BigDecimal $value): FloatValueT
{
    return new FloatValueT(BigDecimal::of($value));
}

/**
 * @api
 * @param null|int|float|numeric-string|BigDecimal $min
 * @param null|int|float|numeric-string|BigDecimal $max
 * @return FloatRangeT<float>
 */
function floatRangeT(null|int|float|string|BigDecimal $min = null, null|int|float|string|BigDecimal $max = null): FloatRangeT
{
    return new FloatRangeT(
        min: $min === null ? null : BigDecimal::of($min),
        max: $max === null ? null : BigDecimal::of($max),
    );
}

const stringT = StringT::T;

const nonEmptyStringT = NonEmptyStringT::T;

const truthyStringT = TruthyStringT::T;

const nonFalsyStringT = TruthyStringT::T;

const numericStringT = NumericStringT::T;

const lowercaseStringT = LowercaseStringT::T;

/**
 * @api
 * @template T of string
 * @param T $value
 * @return StringValueT<T>
 */
function stringT(string $value): StringValueT
{
    return new StringValueT($value);
}

/**
 * @api
 * @template T of object
 * @param class-string<T>|Type<T> $object
 * @return ClassT<T>
 */
function classT(string|Type $object): ClassT
{
    if (\is_string($object)) {
        return new ClassT(namedObjectT($object));
    }

    return new ClassT($object);
}

const numericT = NumericT::T;

const scalarT = ScalarT::T;

const arrayKeyT = ArrayKeyT::T;

const arrayT = ArrayBareT::T;

/**
 * @api
 */
function optional(Type $type): Optional
{
    return new Optional($type);
}

/**
 * @api
 * @template V
 * @param Type<V> $value
 * @return ListT<V>
 */
function listT(Type $value = mixedT): ListT
{
    return new ListT($value);
}

/**
 * @api
 * @template V
 * @param Type<V> $value
 * @return ListT<V>
 */
function nonEmptyListT(Type $value = mixedT): ListT
{
    return new ListT(valueType: $value, isNonEmpty: true);
}

/**
 * @api
 * @param list<Type> $elements
 * @return ListT<list<mixed>>
 */
function listShapeT(array $elements = []): ListT
{
    return new ListT(neverT, $elements);
}

/**
 * @api
 * @param list<Type> $elements
 * @return ListT<mixed>
 */
function unsealedListShapeT(array $elements = [], Type $value = mixedT): ListT
{
    return new ListT($value, $elements);
}

/**
 * @api
 * @template K of array-key
 * @template V
 * @param Type<K> $key
 * @param Type<V> $value
 * @return ArrayT<K, V>
 */
function arrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT($key, $value);
}

/**
 * @api
 * @template K of array-key
 * @template V
 * @param Type<K> $key
 * @param Type<V> $value
 * @return ArrayT<K, V>
 */
function nonEmptyArrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT($key, $value, isNonEmpty: true);
}

/**
 * @api
 * @param array<Type|Optional> $elements
 * @return ArrayT<array-key, mixed>
 */
function arrayShapeT(array $elements = []): ArrayT
{
    return unsealedArrayShapeT($elements, neverT, neverT);
}

/**
 * @api
 * @param array<Type|Optional> $elements
 * @return ArrayT<array-key, mixed>
 */
function unsealedArrayShapeT(array $elements = [], Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT(
        keyType: $key,
        valueType: $value,
        elements: array_map(
            static fn(int|string $key, Type|Optional $type): ArrayElement => new ArrayElement(
                key: $key,
                type: $type instanceof Optional ? $type->type : $type,
                isOptional: $type instanceof Optional,
            ),
            array_keys($elements),
            $elements,
        ),
    );
}

const iterableT = IterableBareT::T;

/**
 * @api
 * @template K
 * @template V
 * @param Type<K> $key
 * @param Type<V> $value
 * @return IterableT<K, V>
 */
function iterableT(Type $key = mixedT, Type $value = mixedT): IterableT
{
    return new IterableT($key, $value);
}

const objectT = ObjectT::T;

/**
 * @api
 * @template T of object
 * @param class-string<T> $class
 * @param list<Type> $templateArguments
 * @return NamedObjectT<T>
 */
function namedObjectT(string $class, array $templateArguments = []): NamedObjectT
{
    return new NamedObjectT($class, $templateArguments);
}

/**
 * @api
 * @param array<non-empty-string, Type|Optional> $props
 * @return ObjectShapeT<object>
 */
function objectShapeT(array $props = []): ObjectShapeT
{
    return new ObjectShapeT(
        array_map(
            static fn(string $name, Type|Optional $type): Property => new Property(
                name: $name,
                type: $type instanceof Optional ? $type->type : $type,
                isOptional: $type instanceof Optional,
            ),
            array_keys($props),
            $props,
        ),
    );
}

const callableT = CallableBareT::T;

/**
 * @api
 * @param list<Parameter|Type> $params
 * @return CallableT<callable>
 */
function callableT(array $params = [], Type $return = mixedT): CallableT
{
    return new CallableT(
        parameters: array_map(
            static fn(Parameter|Type $param): Parameter => $param instanceof Parameter ? $param : new Parameter($param),
            $params,
        ),
        returnType: $return,
    );
}

/**
 * @api
 * @param list<Parameter|Type> $params
 * @return ClosureT<\Closure>
 */
function closureT(array $params = [], Type $return = mixedT): ClosureT
{
    return new ClosureT(
        parameters: array_map(
            static fn(Parameter|Type $param): Parameter => $param instanceof Parameter ? $param : new Parameter($param),
            $params,
        ),
        returnType: $return,
    );
}

/**
 * @api
 * @param ?non-empty-string $name
 */
function param(Type $type, bool $default = false, bool $byRef = false, bool $variadic = false, ?string $name = null): Parameter
{
    return new Parameter(
        type: $type,
        hasDefault: $default,
        isPassedByReference: $byRef,
        isVariadic: $variadic,
        name: $name,
    );
}

const resourceT = ResourceT::T;

/**
 * @api
 * @param non-empty-string $name
 */
function constantT(string $name): ConstantT
{
    return new ConstantT($name);
}

/**
 * @api
 * @param non-empty-string $mask
 */
function constantMaskT(string $mask): ConstantMaskT
{
    return new ConstantMaskT(new Mask($mask));
}

/**
 * @api
 * @param class-string $class
 * @param non-empty-string $name
 */
function classConstantT(string $class, string $name): ClassConstantT
{
    return new ClassConstantT($class, $name);
}

/**
 * @api
 * @param class-string $class
 * @param non-empty-string $mask
 */
function classConstantMaskT(string $class, string $mask): ClassConstantMaskT
{
    return new ClassConstantMaskT($class, new Mask($mask));
}

/**
 * @api
 * @no-named-arguments
 * @param Type|non-empty-list<Type> $types
 */
function intersectionT(Type|array $types, Type ...$moreTypes): IntersectionT
{
    return new IntersectionT([...(\is_array($types) ? $types : [$types]), ...$moreTypes]);
}

/**
 * @api
 * @no-named-arguments
 * @param Type|non-empty-list<Type> $types
 */
function andT(Type|array $types, Type ...$moreTypes): IntersectionT
{
    return intersectionT($types, ...$moreTypes);
}

/**
 * @api
 * @no-named-arguments
 * @template T
 * @param Type<T>|non-empty-list<Type<T>> $types
 * @param Type<T> ...$moreTypes
 * @return UnionT<T>
 */
function unionT(Type|array $types, Type ...$moreTypes): UnionT
{
    return new UnionT([...(\is_array($types) ? $types : [$types]), ...$moreTypes]);
}

/**
 * @api
 * @no-named-arguments
 * @template T
 * @param Type<T>|non-empty-list<Type<T>> $types
 * @param Type<T> ...$moreTypes
 * @return UnionT<T>
 */
function orT(Type|array $types, Type ...$moreTypes): UnionT
{
    return unionT($types, ...$moreTypes);
}

/**
 * @api
 * @template T
 * @param Type<T> $type
 * @return UnionT<null|T>
 */
function nullOrT(Type $type): UnionT
{
    return new UnionT([nullT, $type]);
}

const mixedT = MixedT::T;
