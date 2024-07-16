<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\DeclarationId\AliasId;
use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\AnonymousFunctionId;
use Typhoon\DeclarationId\ConstantId;
use Typhoon\DeclarationId\Id;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\DeclarationId\NamedFunctionId;
use Typhoon\DeclarationId\TemplateId;
use Typhoon\Type\Internal\UnionType;

/**
 * @api
 * @psalm-immutable
 * @implements Type<mixed>
 * @todo reorder methods according to visitor
 */
enum types implements Type
{
    case never;
    case void;
    case null;
    case true;
    case false;
    case bool;
    case int;
    case negativeInt;
    case nonPositiveInt;
    case nonNegativeInt;
    case positiveInt;
    case nonZeroInt;
    case literalInt;
    case float;
    case literalFloat;
    case string;
    case callableString;
    case nonEmptyString;
    case lowercaseString;
    case numericString;
    case truthyString;
    public const nonFalsyString = self::truthyString;
    case literalString;
    case classString;
    case arrayKey;
    case numeric;
    case resource;
    case array;
    case iterable;
    case object;
    case callable;
    case closure;
    case scalar;
    case mixed;

    /**
     * @no-named-arguments
     */
    public static function alias(AliasId $alias, Type ...$arguments): Type
    {
        return new Internal\AliasType($alias, $arguments);
    }

    /**
     * @no-named-arguments
     * @param non-empty-string|NamedClassId|AnonymousClassId $class
     * @param non-empty-string $name
     */
    public static function classAlias(string|NamedClassId|AnonymousClassId $class, string $name, Type ...$arguments): Type
    {
        return new Internal\AliasType(Id::alias($class, $name), $arguments);
    }

    /**
     * @param non-empty-string $name
     */
    public static function arg(string $name): Argument
    {
        return new Argument($name);
    }

    /**
     * @return Type<non-empty-array<mixed>>
     */
    public static function nonEmptyArray(Type $key = self::arrayKey, Type $value = self::mixed): Type
    {
        return new Internal\NonEmptyArrayType($key, $value);
    }

    /**
     * @return Type<array<mixed>>
     */
    public static function array(Type $key = self::arrayKey, Type $value = self::mixed): Type
    {
        if ($key === self::arrayKey && $value === self::mixed) {
            return self::array;
        }

        return new Internal\ArrayType($key, $value, []);
    }

    /**
     * @param array<Type|ShapeElement> $elements
     * @return Type<array<mixed>>
     */
    public static function arrayShape(array $elements = []): Type
    {
        return self::unsealedArrayShape($elements, self::never, self::never);
    }

    /**
     * @param array<Type|ShapeElement> $elements
     * @return Type<array<mixed>>
     */
    public static function unsealedArrayShape(array $elements = [], Type $key = self::arrayKey, Type $value = self::mixed): Type
    {
        return new Internal\ArrayType($key, $value, array_map(
            static fn(Type|ShapeElement $element): ShapeElement => $element instanceof Type ? new ShapeElement($element) : $element,
            $elements,
        ));
    }

    /**
     * @template TReturn
     * @param list<Type|Parameter> $parameters
     * @param Type<TReturn> $return
     * @return Type<callable>
     */
    public static function callable(array $parameters = [], Type $return = self::mixed): Type
    {
        if ($parameters === [] && $return === self::mixed) {
            return self::callable;
        }

        return new Internal\CallableType(
            array_map(
                static fn(Type|Parameter $parameter): Parameter => $parameter instanceof Type ? new Parameter($parameter) : $parameter,
                $parameters,
            ),
            $return,
        );
    }

    /**
     * @param non-empty-string|NamedClassId|Type $class
     * @param non-empty-string $name
     */
    public static function classConstant(string|NamedClassId|Type $class, string $name): Type
    {
        if (!$class instanceof Type) {
            $class = self::object($class);
        }

        if (str_ends_with($name, '*')) {
            return new Internal\ClassConstantMaskType($class, substr($name, 0, -1));
        }

        return new Internal\ClassConstantType($class, $name);
    }

    /**
     * @param non-empty-string|NamedClassId|Type $class
     */
    public static function classConstantMask(string|NamedClassId|Type $class, string $namePrefix): Type
    {
        if (!$class instanceof Type) {
            $class = self::object($class);
        }

        return new Internal\ClassConstantMaskType($class, $namePrefix);
    }

    public static function classString(Type $object): Type
    {
        return new Internal\ClassStringType($object);
    }

    /**
     * @param list<Type|Parameter> $parameters
     * @return Type<\Closure>
     */
    public static function closure(array $parameters = [], Type $return = self::mixed): Type
    {
        if ($parameters === [] && $return === self::mixed) {
            return self::closure;
        }

        return new Internal\IntersectionType([
            self::closure,
            new Internal\CallableType(
                array_map(
                    static fn(Type|Parameter $parameter): Parameter => $parameter instanceof Type ? new Parameter($parameter) : $parameter,
                    $parameters,
                ),
                $return,
            ),
        ]);
    }

    public static function conditional(Argument|Type $subject, Type $if, Type $then, Type $else): Type
    {
        return new Internal\ConditionalType($subject, $if, $then, $else);
    }

    /**
     * @param non-empty-string|ConstantId $name
     */
    public static function constant(string|ConstantId $name): Type
    {
        if (!$name instanceof ConstantId) {
            $name = Id::constant($name);
        }

        return new Internal\ConstantType($name);
    }

    /**
     * @no-named-arguments
     */
    public static function intersection(Type ...$types): Type
    {
        return match (\count($types)) {
            0 => self::never,
            1 => $types[0],
            default => new Internal\IntersectionType($types),
        };
    }

    /**
     * @no-named-arguments
     * @param positive-int $value
     * @param positive-int ...$values
     * @return Type<positive-int>
     */
    public static function intMask(int $value, int ...$values): Type
    {
        if ($values === []) {
            return new Internal\IntMaskType(new Internal\IntType($value, $value));
        }

        return new Internal\IntMaskType(new UnionType(array_map(
            static fn(int $value): Internal\IntType => new Internal\IntType($value, $value),
            [$value, ...$values],
        )));
    }

    /**
     * @return Type<int>
     */
    public static function intMaskOf(Type $type): Type
    {
        return new Internal\IntMaskType($type);
    }

    /**
     * @return Type<int>
     */
    public static function intRange(?int $min = null, ?int $max = null): Type
    {
        return match (true) {
            $min === null && $max === null => self::int,
            $min === null && $max === -1 => self::negativeInt,
            $min === null && $max === 0 => self::nonPositiveInt,
            $min === 0 && $max === null => self::nonNegativeInt,
            $min === 1 && $max === null => self::positiveInt,
            default => new Internal\IntType($min, $max),
        };
    }

    /**
     * @template TKey
     * @template TValue
     * @param Type<TKey> $key
     * @param Type<TValue> $value
     * @return Type<iterable<TKey, TValue>>
     */
    public static function iterable(Type $key = self::mixed, Type $value = self::mixed): Type
    {
        if ($key === self::mixed && $value === self::mixed) {
            return self::iterable;
        }

        return new Internal\IterableType($key, $value);
    }

    public static function keyOf(Type $type): Type
    {
        return new Internal\KeyType($type);
    }

    /**
     * @return Type<list<mixed>>
     */
    public static function list(Type $value = self::mixed): Type
    {
        return new Internal\ListType($value, []);
    }

    /**
     * @param list<Type|ShapeElement> $elements
     * @return Type<list<mixed>>
     */
    public static function listShape(array $elements = []): Type
    {
        return self::unsealedListShape($elements, self::never);
    }

    /**
     * @param array<non-negative-int, Type|ShapeElement> $elements
     * @return Type<list<mixed>>
     */
    public static function unsealedListShape(array $elements = [], Type $value = self::mixed): Type
    {
        return new Internal\ListType($value, array_map(
            static fn(Type|ShapeElement $element): ShapeElement => $element instanceof Type ? new ShapeElement($element) : $element,
            $elements,
        ));
    }

    /**
     * @template TType
     * @param Type<TType> $type
     * @return Type<TType>
     */
    public static function literal(Type $type): Type
    {
        return new Internal\LiteralType($type);
    }

    /**
     * @template TValue of int
     * @param TValue $value
     * @return Type<TValue>
     */
    public static function int(int $value): Type
    {
        /** @var Internal\IntType<TValue> */
        return new Internal\IntType($value, $value);
    }

    /**
     * @template TValue of float
     * @param TValue $value
     * @return Type<TValue>
     */
    public static function float(float $value): Type
    {
        return new Internal\FloatValueType($value);
    }

    /**
     * @template TValue of string
     * @param TValue $value
     * @return Type<TValue>
     */
    public static function string(string $value): Type
    {
        return new Internal\StringValueType($value);
    }

    /**
     * @return Type<non-empty-list<mixed>>
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public static function nonEmptyList(Type $value = self::mixed): Type
    {
        /** @phpstan-ignore return.type */
        return new Internal\ListType($value, [new ShapeElement($value)]);
    }

    /**
     * @template TType
     * @param Type<TType> $type
     * @return Type<?TType>
     */
    public static function nullable(Type $type): Type
    {
        return new UnionType([self::null, $type]);
    }

    /**
     * @no-named-arguments
     * @param non-empty-string|NamedClassId $class
     * @return Type<object>
     */
    public static function object(string|NamedClassId $class, Type ...$arguments): Type
    {
        if (\is_string($class)) {
            $class = Id::namedClass($class);
        }

        if ($class->name === \Closure::class) {
            \assert($arguments === [], 'Closure type arguments are not supported');

            return self::closure;
        }

        return new Internal\NamedObjectType($class, $arguments);
    }

    /**
     * @param array<string, Type|ShapeElement> $properties
     * @return Type<object>
     */
    public static function objectShape(array $properties = []): Type
    {
        if ($properties === []) {
            return self::object;
        }

        return new Internal\ObjectType(array_map(
            static fn(Type|ShapeElement $property): ShapeElement => $property instanceof Type ? new ShapeElement($property) : $property,
            $properties,
        ));
    }

    public static function generator(Type $key = self::mixed, Type $value = self::mixed, Type $send = self::mixed, Type $return = self::mixed): Type
    {
        return new Internal\NamedObjectType(Id::namedClass(\Generator::class), [$key, $value, $send, $return]);
    }

    public static function offset(Type $type, Type $offset): Type
    {
        return new Internal\OffsetType($type, $offset);
    }

    /**
     * @template TType
     * @param Type<TType> $type
     * @param ?non-empty-string $name
     * @return Parameter<TType>
     */
    public static function param(Type $type = self::mixed, bool $hasDefault = false, bool $variadic = false, bool $byReference = false, ?string $name = null): Parameter
    {
        return new Parameter($type, $hasDefault, $variadic, $byReference, $name);
    }

    /**
     * @template TType
     * @param Type<TType> $type
     * @return ShapeElement<TType>
     */
    public static function optional(Type $type): ShapeElement
    {
        return new ShapeElement($type, true);
    }

    /**
     * @no-named-arguments
     * @param null|non-empty-string|NamedClassId|AnonymousClassId $resolvedClass
     */
    public static function self(null|string|NamedClassId|AnonymousClassId $resolvedClass = null, Type ...$arguments): Type
    {
        if (\is_string($resolvedClass)) {
            $resolvedClass = Id::class($resolvedClass);
        }

        return new Internal\SelfType($resolvedClass, $arguments);
    }

    /**
     * @no-named-arguments
     * @param null|non-empty-string|NamedClassId $resolvedClass
     */
    public static function parent(null|string|NamedClassId $resolvedClass = null, Type ...$arguments): Type
    {
        if (\is_string($resolvedClass)) {
            $resolvedClass = Id::namedClass($resolvedClass);
        }

        return new Internal\ParentType($resolvedClass, $arguments);
    }

    /**
     * @no-named-arguments
     * @param null|non-empty-string|NamedClassId|AnonymousClassId $resolvedClass
     */
    public static function static(null|string|NamedClassId|AnonymousClassId $resolvedClass = null, Type ...$arguments): Type
    {
        if (\is_string($resolvedClass)) {
            $resolvedClass = Id::class($resolvedClass);
        }

        return new Internal\StaticType($resolvedClass, $arguments);
    }

    public static function template(TemplateId $id): Type
    {
        return new Internal\TemplateType($id);
    }

    /**
     * @param non-empty-string|NamedFunctionId|AnonymousFunctionId $function
     * @param non-empty-string $name
     */
    public static function functionTemplate(string|NamedFunctionId|AnonymousFunctionId $function, string $name): Type
    {
        if (\is_string($function)) {
            $function = Id::namedFunction($function);
        }

        return new Internal\TemplateType(Id::template($function, $name));
    }

    public static function scalar(bool|int|float|string $value): Type
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return match (true) {
            $value === true => self::true,
            $value === false => self::false,
            \is_int($value) => new Internal\IntType($value, $value),
            \is_float($value) => new Internal\FloatValueType($value),
            default => new Internal\StringValueType($value),
        };
    }

    /**
     * @param non-empty-string|NamedClassId|AnonymousClassId $class
     * @param non-empty-string $name
     */
    public static function classTemplate(string|NamedClassId|AnonymousClassId $class, string $name): Type
    {
        if (\is_string($class)) {
            $class = Id::class($class);
        }

        return new Internal\TemplateType(Id::template($class, $name));
    }

    /**
     * @param non-empty-string|NamedClassId|AnonymousClassId $class
     * @param non-empty-string $method
     * @param non-empty-string $name
     */
    public static function methodTemplate(string|NamedClassId|AnonymousClassId $class, string $method, string $name): Type
    {
        return new Internal\TemplateType(Id::template(Id::method($class, $method), $name));
    }

    /**
     * @no-named-arguments
     * @template TType
     * @param Type<TType> ...$types
     * @return Type<TType>
     */
    public static function union(Type ...$types): Type
    {
        return match (\count($types)) {
            0 => self::never,
            1 => $types[0],
            default => new UnionType($types),
        };
    }

    public static function valueOf(Type $type): Type
    {
        return self::offset($type, self::keyOf($type));
    }

    /**
     * @template TType
     * @param Type<TType> $type
     * @return Type<TType>
     */
    public static function varianceAware(Type $type, Variance $variance): Type
    {
        return new Internal\VarianceAwareType($type, $variance);
    }

    public static function not(Type $type): Type
    {
        return new Internal\NotType($type);
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        // most common types should come first
        return match ($this) {
            self::null => $visitor->null($this),
            self::true => $visitor->true($this),
            self::false => $visitor->false($this),
            self::bool => $visitor->union($this, [self::true, self::false]),
            self::int => $visitor->int($this, null, null),
            self::float => $visitor->float($this),
            self::string => $visitor->string($this),
            self::array => $visitor->array($this, self::arrayKey, self::mixed, []),
            self::iterable => $visitor->iterable($this, self::mixed, self::mixed),
            self::object => $visitor->object($this, []),
            self::mixed => $visitor->mixed($this),
            self::void => $visitor->void($this),
            self::never => $visitor->never($this),
            self::callable => $visitor->callable($this, [], self::mixed),
            self::closure => $visitor->namedObject($this, Id::namedClass(\Closure::class), []),
            self::nonEmptyString => $visitor->intersection($this, [
                self::string,
                new Internal\NotType(new Internal\StringValueType('')),
            ]),
            self::callableString => $visitor->intersection($this, [self::string, self::callable]),
            self::resource => $visitor->resource($this),
            self::negativeInt => $visitor->int($this, null, -1),
            self::nonPositiveInt => $visitor->int($this, null, 0),
            self::nonNegativeInt => $visitor->int($this, 0, null),
            self::positiveInt => $visitor->int($this, 1, null),
            self::classString => $visitor->classString($this, types::object),
            self::arrayKey => $visitor->union($this, [self::int, self::string]),
            self::numeric => $visitor->numeric($this),
            self::numericString => $visitor->intersection($this, [self::string, self::numeric]),
            self::scalar => $visitor->union($this, [self::bool, self::int, self::float, self::string]),
            self::lowercaseString => $visitor->lowercaseString($this),
            self::truthyString => $visitor->intersection($this, [
                self::string,
                new Internal\NotType(new Internal\StringValueType('')),
                new Internal\NotType(new Internal\StringValueType('0')),
            ]),
            self::literalInt => $visitor->literal($this, self::int),
            self::literalFloat => $visitor->literal($this, self::float),
            self::literalString => $visitor->literal($this, self::string),
            self::nonZeroInt => $visitor->union($this, [self::positiveInt, self::negativeInt]),
        };
    }
}
