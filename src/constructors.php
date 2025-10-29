<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Generator\Generator;
use Typhoon\Type\Internal\Optional;
use function Typhoon\floatToString;

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
 * @return IntT|IntValueT<int>|IntRangeT<int>
 */
function intRangeT(?int $min = null, ?int $max = null): IntT|IntValueT|IntRangeT
{
    if ($min === $max) {
        if ($min === null) {
            return intT;
        }

        return new IntValueT($min);
    }

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
 * @param int|Type|list<int|Type> $ints
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
 * @param int|Type|list<int|Type> $ints
 * @return BitmaskT<int>
 */
function intMaskT(int|Type|array $ints, int|Type ...$moreInts): BitmaskT
{
    return bitmaskT($ints, ...$moreInts);
}

const floatT = FloatT::T;

const MINUS_INF = -INF;
const MINUS_INF_NAME = __NAMESPACE__ . '\MINUS_INF';

/**
 * @api
 * @template T of float = never
 * @param numeric-string|T $value
 * @return ($value is T ? Type<T> : Type<float>)
 */
function floatT(float|string $value): Type
{
    return match (true) {
        \is_string($value) => new FloatValueT($value),
        is_nan($value) => constantT('NAN'),
        $value === -INF => constantT(MINUS_INF_NAME),
        $value === INF => constantT('INF'),
        default => new FloatValueT(floatToString($value)),
    };
}

/**
 * @api
 * @param null|float|numeric-string $min
 * @param null|float|numeric-string $max
 * @return Type<float>
 */
function floatRangeT(null|float|string $min = null, null|float|string $max = null): Type
{
    $min = match (true) {
        \is_float($min) => match (true) {
            is_nan($min) => 'NAN',
            $min === -INF => MINUS_INF_NAME,
            $min === INF => 'INF',
            default => floatToString($min),
        },
        $min === null => MINUS_INF_NAME,
        default => $min,
    };
    $max = match (true) {
        \is_float($max) => match (true) {
            is_nan($max) => 'NAN',
            $max === -INF => MINUS_INF_NAME,
            $max === INF => 'INF',
            default => floatToString($max),
        },
        $max === null => 'INF',
        default => $min,
    };

    if ($min === $max) {
        if (is_numeric($min)) {
            return new FloatValueT($min);
        }

        /** @var ConstantT<float> */
        return new ConstantT($min);
    }

    if ($min === 'NAN' || $max === 'NAN') {
        return neverT;
    }

    if ($min === MINUS_INF_NAME && $max === 'INF') {
        return floatT;
    }

    return new FloatRangeT(
        min: is_numeric($min) ? $min : null,
        max: is_numeric($max) ? $max : null,
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

const literalStringT = LiteralStringT::T;

const numericT = NumericT::T;

const scalarT = ScalarT::T;

const arrayKeyT = ArrayKeyT::T;

const arrayT = ArrayDefaultT::T;

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
 * @return ListT<list<V>>
 */
function listT(Type $value = mixedT): ListT
{
    /** @var ListT<list<V>> */
    return new ListT($value);
}

/**
 * @api
 * @template V
 * @param Type<V> $value
 * @return ListT<non-empty-list<V>>
 */
function nonEmptyListT(Type $value = mixedT): ListT
{
    /** @var ListT<non-empty-list<V>> */
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
 * @return ListT<list<mixed>>
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
 * @return ArrayT<array<K, V>>
 */
function arrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    /** @var ArrayT<array<K, V>> */
    return new ArrayT(keyType: $key, valueType: $value);
}

/**
 * @api
 * @template K of array-key
 * @template V
 * @param Type<K> $key
 * @param Type<V> $value
 * @return ArrayT<non-empty-array<K, V>>
 */
function nonEmptyArrayT(Type $key = arrayKeyT, Type $value = mixedT): ArrayT
{
    /** @var ArrayT<non-empty-array<K, V>> */
    return new ArrayT(keyType: $key, valueType: $value, isNonEmpty: true);
}

/**
 * @api
 * @param array<Type|Optional> $elements
 * @return ArrayT<array<mixed>>
 */
function arrayShapeT(array $elements = []): ArrayT
{
    return unsealedArrayShapeT($elements, neverT, neverT);
}

/**
 * @api
 * @param array<Type|Optional> $elements
 * @return ArrayT<array<mixed>>
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

/**
 * @api
 * @template T
 * @param Type<T> $array
 * @return KeyOfT<T>
 */
function keyT(Type $array): KeyOfT
{
    return new KeyOfT($array);
}

/**
 * @api
 * @template T
 * @param Type<T> $array
 * @return ValueOfT<T>
 */
function valueT(Type $array): ValueOfT
{
    return new ValueOfT($array);
}

/**
 * @api
 * @template T
 * @template K
 * @param Type<T> $array
 * @param Type<K> $key
 * @return OffsetT<T, K>
 */
function offsetT(Type $array, Type $key): OffsetT
{
    return new OffsetT($array, $key);
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
 * @param list<class-string|NamedObjectT> $supertypes
 * @param array<non-empty-string, Type|Optional> $props
 * @return ObjectT<object>
 */
function objectT(array $templates = [], array $supertypes = [], array $props = []): ObjectT
{
    return new ObjectT(
        templates: $templates,
        supertypes: array_map(
            static fn(string|NamedObjectT $s): NamedObjectT => $s instanceof NamedObjectT ? $s : new NamedObjectT($s),
            $supertypes,
        ),
        properties: array_map(
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

/**
 * @api
 * @param array<non-empty-string, Type|Optional> $props
 * @return ObjectT<object>
 */
function objectShapeT(array $props = []): ObjectT
{
    return objectT(props: $props);
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
 * @param list<Parameter|Type> $params
 * @return CallableT<callable>
 */
function callableT(array $templates = [], array $params = [], Type $return = mixedT): CallableT
{
    return new CallableT(
        templates: $templates,
        parameters: array_map(
            static fn(Parameter|Type $p): Parameter => $p instanceof Parameter ? $p : new Parameter(type: $p),
            $params,
        ),
        returnType: $return,
    );
}

/**
 * @param list<Template<Variance::Invariant>> $templates
 * @param list<Parameter|Type> $params
 * @return ClosureT<\Closure>
 */
function closureT(array $templates = [], array $params = [], Type $return = mixedT): ClosureT
{
    return new ClosureT(
        templates: $templates,
        parameters: array_map(
            static fn(Parameter|Type $p): Parameter => $p instanceof Parameter ? $p : new Parameter(type: $p),
            $params,
        ),
        returnType: $return,
    );
}

/**
 * @api
 * @param ?non-empty-string $name
 */
function param(?string $name = null, Type $type = neverT, bool|Type $default = false, bool|Type $byRef = false, bool $variadic = false): Parameter
{
    return new Parameter(
        name: $name,
        type: $type,
        hasDefault: $default !== false,
        defaultType: $default instanceof Type ? $default : null,
        isPassedByReference: $byRef !== false,
        outType: $byRef instanceof Type ? $byRef : null,
        isVariadic: $variadic,
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
    return new ClassConstantMaskT($class, $mask);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Invariant>
 */
function template(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT, ?Type $default = null): Template
{
    return new Template($name, lowerBound: $lowerBound, upperBound: $upperBound, default: $default);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Covariant>
 */
function templateOut(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT, ?Type $default = null): Template
{
    return new Template($name, Variance::Covariant, $lowerBound, $upperBound, $default);
}

/**
 * @api
 * @param non-empty-string $name
 * @return Template<Variance::Contravariant>
 */
function templateIn(string $name, Type $upperBound = mixedT, Type $lowerBound = neverT, ?Type $default = null): Template
{
    return new Template($name, Variance::Contravariant, $lowerBound, $upperBound, $default);
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
 * @return IsSubtypeT<bool>
 */
function isSupertypeT(Type $left, Type $right): IsSubtypeT
{
    return new IsSubtypeT($right, $left);
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
