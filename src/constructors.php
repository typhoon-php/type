<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Generator\Generator;
use function Typhoon\Type\Internal\floatToString;

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
function intRangeT(?int $min = null, ?int $max = null): IntRangeT
{
    return new IntRangeT(min: $min, max: $max);
}

const negativeIntT = NegativeIntT::T;

const nonPositiveIntT = NonPositiveIntT::T;

const nonZeroInt = NonZeroIntT::T;

const nonNegativeIntT = NonNegativeIntT::T;

const positiveIntT = PositiveIntT::T;

/**
 * @api
 * @no-named-arguments
 * @param int|Type|list<int|Type> $ints
 * @return IntMaskT<int>
 */
function intMaskT(int|Type|array $ints, int|Type ...$moreInts): IntMaskT
{
    return new IntMaskT(orT(array_map(
        static fn(int|Type $int): Type => \is_int($int) ? intT($int) : $int,
        [...(\is_array($ints) ? $ints : [$ints]), ...$moreInts],
    )));
}

const floatT = FloatT::T;

/**
 * @api
 * @template T of float = never
 * @param numeric-string|T $value
 * @return ($value is T ? FloatValueT<T> : FloatValueT<float>)
 */
function floatT(float|string $value): FloatValueT
{
    return new FloatValueT(\is_float($value) ? floatToString($value) : $value);
}

/**
 * @api
 * @param null|float|numeric-string $min
 * @param null|float|numeric-string $max
 * @return FloatRangeT<float>
 */
function floatRangeT(null|float|string $min = null, null|float|string $max = null): FloatRangeT
{
    return new FloatRangeT(
        min: match (true) {
            $min === null => null,
            \is_float($min) => floatToString($min),
            default => $min,
        },
        max: match (true) {
            $max === null => null,
            \is_float($max) => floatToString($max),
            default => $max,
        },
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
 * @return ClassStringT<T>
 */
function classStringT(string|Type $object): ClassStringT
{
    if (\is_string($object)) {
        return new ClassStringT(namedObjectT($object));
    }

    return new ClassStringT($object);
}

const literalStringT = LiteralStringT::T;

const numericT = NumericT::T;

const scalarT = ScalarT::T;

const arrayKeyT = ArrayKeyT::T;

const arrayT = ArrayDefaultT::T;

/**
 * @api
 */
function optional(Type $type): ArrayElement
{
    return new ArrayElement($type, isOptional: true);
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
 * @todo non-empty
 */
function nonEmptyListT(Type $value = mixedT): ListT
{
    return new ListT(value: $value, isNonEmpty: true);
}

/**
 * @api
 * @param list<ArrayElement|Type> $elements
 * @return ListT<mixed>
 */
function listShapeT(array $elements = []): ListT
{
    return unsealedListShapeT($elements, neverT);
}

/**
 * @api
 * @param list<ArrayElement|Type> $elements
 * @return ListT<mixed>
 */
function unsealedListShapeT(array $elements = [], Type $value = mixedT): ListT
{
    return new ListT(
        value: $value,
        elements: array_map(
            static fn(ArrayElement|Type $e) => $e instanceof ArrayElement ? $e : new ArrayElement($e),
            $elements,
        ),
    );
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
    return new ArrayT(key: $key, value: $value);
}

/**
 * @api
 * @template K of array-key
 * @template V
 * @param Type<K> $key
 * @param Type<V> $value
 * @return ArrayT<K, V>
 * @todo non-empty
 */
function nonEmptyArrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    return new ArrayT(key: $key, value: $value, isNonEmpty: true);
}

/**
 * @api
 * @param array<ArrayElement|Type> $elements
 * @return ArrayT<array-key, mixed>
 */
function arrayShapeT(array $elements = []): ArrayT
{
    return unsealedArrayShapeT($elements, neverT, neverT);
}

/**
 * @api
 * @param array<ArrayElement|Type> $elements
 * @return ArrayT<array-key, mixed>
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
 * @template T
 * @param Type<T> $array
 * @return KeyT<T>
 */
function keyT(Type $array): KeyT
{
    return new KeyT($array);
}

/**
 * @api
 * @template T
 * @param Type<T> $array
 * @return ValueT<T>
 */
function valueT(Type $array): ValueT
{
    return new ValueT($array);
}

/**
 * @api
 * @template T
 * @template K
 * @param Type<T> $value
 * @param Type<K> $key
 * @return OffsetT<T, K>
 */
function offsetT(Type $value, Type $key): OffsetT
{
    return new OffsetT($value, $key);
}

const iterableT = IterableDefaultT::T;

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

const objectT = ObjectDefaultT::T;

/**
 * @api
 * @param list<Template> $templates
 * @param list<class-string|NamedObjectT> $superTypes
 * @param list<Property> $properties
 * @return ObjectT<object>
 */
function objectT(array $templates = [], array $superTypes = [], array $properties = []): ObjectT
{
    return new ObjectT(
        $templates,
        array_map(
            static fn(string|NamedObjectT $s): NamedObjectT => $s instanceof NamedObjectT ? $s : new NamedObjectT($s),
            $superTypes,
        ),
        $properties,
    );
}

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
 * @param non-empty-string $name
 */
function prop(string $name, Type $type = mixedT, bool $isOptional = false): Property
{
    return new Property($name, $type, $isOptional);
}

const selfT = SelfDefaultT::T;

/**
 * @api
 * @param list<Type> $templateArguments
 * @return SelfT<object>
 */
function selfT(array $templateArguments = []): SelfT
{
    return new SelfT($templateArguments);
}

const parentT = ParentDefaultT::T;

/**
 * @api
 * @param list<Type> $templateArguments
 * @return ParentT<object>
 */
function parentT(array $templateArguments = []): ParentT
{
    return new ParentT($templateArguments);
}

const staticT = StaticDefaultT::T;

/**
 * @api
 * @param list<Type> $templateArguments
 * @return StaticT<object>
 */
function staticT(array $templateArguments = []): StaticT
{
    return new StaticT($templateArguments);
}

const callableT = CallableDefaultT::T;

/**
 * @param list<Template<Variance::Invariant>> $templates
 * @param list<Parameter|Type> $parameters
 * @return CallableT<callable>
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

const closureT = ClosureDefaultT::T;

/**
 * @param list<Template<Variance::Invariant>> $templates
 * @param list<Parameter|Type> $parameters
 * @return ClosureT<\Closure>
 */
function closureT(array $templates = [], array $parameters = [], Type $returns = mixedT): ClosureT
{
    return new ClosureT(
        templates: $templates,
        parameters: array_map(
            static fn(Parameter|Type $p): Parameter => $p instanceof Parameter ? $p : new Parameter($p),
            $parameters,
        ),
        returns: $returns,
    );
}

/**
 * @api
 */
function param(Type $type, bool $hasDefault = false): Parameter
{
    return new Parameter($type, $hasDefault);
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
 * @param class-string|Type $on
 * @param non-empty-string $name
 */
function classConstantT(string|Type $on, string $name): ClassConstantT
{
    if (\is_string($on)) {
        $on = namedObjectT($on);
    }

    return new ClassConstantT($on, $name);
}

/**
 * @api
 * @param class-string|Type $on
 * @param non-empty-string $namePrefix
 */
function classConstantMaskT(string|Type $on, string $namePrefix): ClassConstantMaskT
{
    if (\is_string($on)) {
        $on = namedObjectT($on);
    }

    return new ClassConstantMaskT($on, $namePrefix);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Invariant>
 */
function template(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT): Template
{
    return new Template($name, lowerBound: $lowerBound, upperBound: $upperBound);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Covariant>
 */
function templateOut(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT): Template
{
    return new Template($name, Variance::Covariant, $lowerBound, $upperBound);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Contravariant>
 */
function templateIn(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT): Template
{
    return new Template($name, Variance::Contravariant, $lowerBound, $upperBound);
}

/**
 * @api
 * @param class-string $class
 * @param non-empty-string $name
 * @param list<Type> $templateArguments
 */
function aliasT(string $class, string $name, array $templateArguments = []): AliasT
{
    return new AliasT($class, $name, $templateArguments);
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
 * @no-named-arguments
 * @template T
 * @param Type<T>|list<Type<T>> $types
 * @param Type<T> ...$moreTypes
 * @return Type<T>
 */
function unionT(Type|array $types, Type ...$moreTypes): Type
{
    $types = [...(\is_array($types) ? $types : [$types]), ...$moreTypes];

    return match (\count($types)) {
        0 => neverT,
        1 => $types[0],
        default => new UnionT($types),
    };
}

/**
 * @api
 * @no-named-arguments
 * @template T
 * @param Type<T>|list<Type<T>> $types
 * @param Type<T> ...$moreTypes
 * @return Type<T>
 */
function orT(Type|array $types, Type ...$moreTypes): Type
{
    return unionT($types, ...$moreTypes);
}

/**
 * @api
 * @template T
 * @param Type<T> $type
 * @return Type<null|T>
 */
function nullOrT(Type $type): Type
{
    return new UnionT([nullT, $type]);
}

/**
 * @api
 * @return IsSubtypeT<bool>
 */
function isSubtypeT(Type $left, Type $right): IsSubtypeT
{
    return new IsSubtypeT($left, $right);
}

/**
 * @api
 * @return IsSupertypeT<bool>
 */
function isSupertypeT(Type $left, Type $right): IsSupertypeT
{
    return new IsSupertypeT($left, $right);
}

/**
 * @api
 * @template Then
 * @template Else
 * @param Type<Then> $then
 * @param Type<Else> $else
 * @return TernaryT<Then|Else>
 */
function ternaryT(Type $condition, Type $then, Type $else): TernaryT
{
    return new TernaryT($condition, $then, $else);
}

/**
 * @api
 */
function literalT(Type $type): LiteralT
{
    return new LiteralT($type);
}

const mixedT = MixedT::T;

/**
 * @api
 * @template T
 * @param T $value
 * @return Type<T>
 */
function of(mixed $value): Type
{
    /** @phpstan-ignore match.unhandled, return.type */
    return match (true) {
        $value === null => nullT,
        $value === false => falseT,
        $value === true => trueT,
        \is_int($value) => intT($value),
        \is_float($value) => floatT($value),
        \is_string($value) => stringT($value),
        \is_array($value) => arrayShapeT(array_map(of(...), $value)),
        /** @phpstan-ignore argument.type, argument.templateType */
        \is_object($value) => namedObjectT($value::class),
        \is_resource($value) => resourceT,
    };
}
