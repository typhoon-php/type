<?php

declare(strict_types=1);

namespace ExtendedTypeSystem;

/**
 * @psalm-api
 */
final class php
{
    public const never = Type\NeverType::self;
    public const void = Type\VoidType::self;
    public const null = Type\NullType::self;
    public const false = Type\FalseType::self;
    public const true = Type\TrueType::self;
    public const bool = Type\BoolType::self;
    public const literalInt = Type\LiteralIntType::self;
    public const int = Type\IntType::self;
    public const float = Type\FloatType::self;
    public const literalString = Type\LiteralStringType::self;
    public const numericString = _numericString;
    public const classString = Type\ClassStringType::self;
    public const callableString = Type\CallableStringType::self;
    public const interfaceString = Type\InterfaceStringType::self;
    public const enumString = Type\EnumStringType::self;
    public const traitString = Type\TraitStringType::self;
    public const nonEmptyString = Type\NonEmptyStringType::self;
    public const string = Type\StringType::self;
    public const numeric = Type\NumericType::self;
    public const scalar = _scalar;
    public const callableArray = Type\CallableArrayType::self;
    public const object = Type\ObjectType::self;
    public const resource = Type\ResourceType::self;
    public const closedResource = Type\ClosedResourceType::self;
    public const arrayKey = _arrayKey;
    public const mixed = Type\MixedType::self;

    /**
     * @psalm-suppress UnusedConstructor
     */
    private function __construct()
    {
    }

    /**
     * @psalm-pure
     * @template TValue of literal-int
     * @param TValue $value
     * @return Type\IntLiteralType<TValue>
     */
    public static function intLiteral(int $value): Type\IntLiteralType
    {
        return new Type\IntLiteralType($value);
    }

    /**
     * @psalm-pure
     */
    public static function intRange(?int $min = null, ?int $max = null): Type\IntRangeType
    {
        return new Type\IntRangeType($min, $max);
    }

    /**
     * @psalm-pure
     * @template TValue of float
     * @param TValue $value
     * @return Type\FloatLiteralType<TValue>
     */
    public static function floatLiteral(float $value): Type\FloatLiteralType
    {
        return new Type\FloatLiteralType($value);
    }

    /**
     * @psalm-pure
     * @template TValue of literal-string
     * @param TValue $value
     * @return Type\StringLiteralType<TValue>
     */
    public static function stringLiteral(string $value): Type\StringLiteralType
    {
        return new Type\StringLiteralType($value);
    }

    /**
     * @psalm-pure
     * @template TObject of object
     * @param Type<TObject> $type
     * @return Type\NamedClassStringType<TObject>
     */
    public static function namedClassString(Type $type): Type\NamedClassStringType
    {
        return new Type\NamedClassStringType($type);
    }

    /**
     * @psalm-pure
     * @return Type\ShapeType<array{}>
     */
    public static function emptyArray(): Type\ShapeType
    {
        /** @var Type\ShapeType<array{}> */
        return new Type\ShapeType();
    }

    /**
     * @psalm-pure
     * @template TValue
     * @param Type<TValue> $valueType
     * @return Type\NonEmptyListType<TValue>
     */
    public static function nonEmptyList(Type $valueType = self::mixed): Type\NonEmptyListType
    {
        return new Type\NonEmptyListType($valueType);
    }

    /**
     * @psalm-pure
     * @template TValue
     * @param Type<TValue> $valueType
     * @return Type\ListType<TValue>
     */
    public static function list(Type $valueType = self::mixed): Type\ListType
    {
        return new Type\ListType($valueType);
    }

    /**
     * @psalm-pure
     * @param array<Type|Type\ShapeElement> $items
     */
    public static function shape(array $items = [], bool $sealed = true): Type\ShapeType
    {
        return new Type\ShapeType($items, $sealed);
    }

    /**
     * @psalm-pure
     * @param array<Type|Type\ShapeElement> $items
     */
    public static function unsealedShape(array $items = []): Type\ShapeType
    {
        return new Type\ShapeType($items, sealed: false);
    }

    /**
     * @psalm-pure
     * @template TType
     * @param Type<TType> $type
     * @return Type\ShapeElement<TType>
     */
    public static function optionalKey(Type $type): Type\ShapeElement
    {
        return new Type\ShapeElement($type, optional: true);
    }

    /**
     * @psalm-pure
     * @template TKey of array-key
     * @template TValue
     * @param Type<TKey> $keyType
     * @param Type<TValue> $valueType
     * @return Type\NonEmptyArrayType<TKey, TValue>
     */
    public static function nonEmptyArray(Type $keyType = self::arrayKey, Type $valueType = self::mixed): Type\NonEmptyArrayType
    {
        return new Type\NonEmptyArrayType($keyType, $valueType);
    }

    /**
     * @psalm-pure
     * @template TKey of array-key
     * @template TValue
     * @param Type<TKey> $keyType
     * @param Type<TValue> $valueType
     * @return Type\ArrayType<TKey, TValue>
     */
    public static function array(Type $keyType = self::arrayKey, Type $valueType = self::mixed): Type\ArrayType
    {
        return new Type\ArrayType($keyType, $valueType);
    }

    /**
     * @psalm-pure
     * @template TKey
     * @template TValue
     * @param Type<TKey> $keyType
     * @param Type<TValue> $valueType
     * @return Type\IterableType<TKey, TValue>
     */
    public static function iterable(Type $keyType = self::mixed, Type $valueType = self::mixed): Type\IterableType
    {
        return new Type\IterableType($keyType, $valueType);
    }

    /**
     * @psalm-pure
     * @no-named-arguments
     * @template TObject of object
     * @param class-string<TObject> $class
     * @return Type\NamedObjectType<TObject>
     */
    public static function objectOf(string $class, Type ...$templateArguments): Type\NamedObjectType
    {
        return new Type\NamedObjectType($class, ...$templateArguments);
    }

    /**
     * @psalm-pure
     * @no-named-arguments
     * @template TObject of object
     * @param class-string<TObject> $declaringClass
     * @return Type\StaticType<TObject>
     */
    public static function static(string $declaringClass, Type ...$templateArguments): Type\StaticType
    {
        return new Type\StaticType($declaringClass, ...$templateArguments);
    }

    /**
     * @psalm-pure
     * @template TType
     * @param Type<TType> $type
     * @return Type\Parameter<TType>
     */
    public static function defaultParam(Type $type = self::mixed): Type\Parameter
    {
        return new Type\Parameter($type, default: true);
    }

    /**
     * @psalm-pure
     * @template TType
     * @param Type<TType> $type
     * @return Type\Parameter<TType>
     */
    public static function variadicParam(Type $type = self::mixed): Type\Parameter
    {
        return new Type\Parameter($type, variadic: true);
    }

    /**
     * @psalm-pure
     * @template TReturn
     * @param list<Type|Type\Parameter> $parameters
     * @param Type<TReturn> $returnType
     * @return Type\ClosureType<TReturn>
     */
    public static function closure(array $parameters = [], ?Type $returnType = null): Type\ClosureType
    {
        return new Type\ClosureType($parameters, $returnType);
    }

    /**
     * @psalm-pure
     * @template TReturn
     * @param list<Type|Type\Parameter> $parameters
     * @param Type<TReturn> $returnType
     * @return Type\CallableType<TReturn>
     */
    public static function callable(array $parameters = [], ?Type $returnType = null): Type\CallableType
    {
        return new Type\CallableType($parameters, $returnType);
    }

    /**
     * @psalm-pure
     * @param non-empty-string $constant
     */
    public static function constant(string $constant): Type\ConstantType
    {
        return new Type\ConstantType($constant);
    }

    /**
     * @psalm-pure
     * @param class-string $class
     * @param non-empty-string $constant
     */
    public static function classConstant(string $class, string $constant): Type\ClassConstantType
    {
        return new Type\ClassConstantType($class, $constant);
    }

    /**
     * @psalm-pure
     */
    public static function keyOf(Type $type): Type\KeyOfType
    {
        return new Type\KeyOfType($type);
    }

    /**
     * @psalm-pure
     */
    public static function valueOf(Type $type): Type\ValueOfType
    {
        return new Type\ValueOfType($type);
    }

    /**
     * @psalm-pure
     * @param non-empty-string $name
     * @param class-string $class
     */
    public static function classTemplate(string $name, string $class): Type\ClassTemplateType
    {
        return new Type\ClassTemplateType($name, $class);
    }

    /**
     * @psalm-pure
     * @no-named-arguments
     */
    public static function intersection(Type $type1, Type $type2, Type ...$moreTypes): Type\IntersectionType
    {
        return new Type\IntersectionType($type1, $type2, ...$moreTypes);
    }

    /**
     * @psalm-pure
     * @no-named-arguments
     * @template TType
     * @param Type<TType> $type1
     * @param Type<TType> $type2
     * @param Type<TType> ...$moreTypes
     * @return Type\UnionType<TType>
     */
    public static function union(Type $type1, Type $type2, Type ...$moreTypes): Type\UnionType
    {
        return new Type\UnionType($type1, $type2, ...$moreTypes);
    }
}

/**
 * @var Type<array-key>
 * @internal
 * @psalm-internal ExtendedTypeSystem
 */
const _arrayKey = new Type\UnionType(php::int, php::string);

/**
 * @var Type<numeric-string>
 * @internal
 * @psalm-internal ExtendedTypeSystem
 */
const _numericString = new Type\IntersectionType(php::string, php::numeric);

/**
 * @var Type<scalar>
 * @internal
 * @psalm-internal ExtendedTypeSystem
 */
const _scalar = new Type\UnionType(php::bool, php::int, php::float, php::string);
