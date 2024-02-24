<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant TReturn
 * @implements TypeVisitor<TReturn>
 */
final class TypeMatcher implements TypeVisitor
{
    /** @var \Closure(Type, non-empty-string, non-empty-string, TypeVisitor): TReturn */
    private readonly \Closure $alias;

    /** @var \Closure(Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $anyLiteral;

    /** @var \Closure(Type<array<mixed>>, Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $array;

    /** @var \Closure(Type<array<mixed>>, array<ArrayElement>, bool, TypeVisitor): TReturn */
    private readonly \Closure $arrayShape;

    /** @var \Closure(Type<bool>, TypeVisitor): TReturn */
    private readonly \Closure $bool;

    /** @var \Closure(Type<callable>, list<Parameter>, Type, TypeVisitor): TReturn */
    private readonly \Closure $callable;

    /** @var \Closure(Type, Type, non-empty-string, TypeVisitor): TReturn */
    private readonly \Closure $classConstant;

    /** @var \Closure(Type<class-string>, TypeVisitor): TReturn */
    private readonly \Closure $classString;

    /** @var \Closure(Type<non-empty-string>, non-empty-string, TypeVisitor): TReturn */
    private readonly \Closure $classStringLiteral;

    /** @var \Closure(Type<\Closure>, list<Parameter>, Type, TypeVisitor): TReturn */
    private readonly \Closure $closure;

    /** @var \Closure(Type, Argument|Type, Type, Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $conditional;

    /** @var \Closure(Type, non-empty-string, TypeVisitor): TReturn */
    private readonly \Closure $constant;

    /** @var \Closure(Type<float>, TypeVisitor): TReturn */
    private readonly \Closure $float;

    /** @var \Closure(Type<int>, TypeVisitor): TReturn */
    private readonly \Closure $int;

    /** @var \Closure(Type, non-empty-list<Type>, TypeVisitor): TReturn */
    private readonly \Closure $intersection;

    /** @var \Closure(Type<int>, Type, TypeVisitor): TReturn */
    private readonly \Closure $intMask;

    /** @var \Closure(Type<int>, ?int, ?int, TypeVisitor): TReturn */
    private readonly \Closure $intRange;

    /** @var \Closure(Type<iterable<mixed>>, Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $iterable;

    /** @var \Closure(Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $key;

    /** @var \Closure(Type<list<mixed>>, Type, TypeVisitor): TReturn */
    private readonly \Closure $list;

    /** @var \Closure(Type, string|int|float|bool, TypeVisitor): TReturn */
    private readonly \Closure $literal;

    /** @var \Closure(Type, TypeVisitor): TReturn */
    private readonly \Closure $mixed;

    /** @var \Closure(Type<non-empty-string>, Type, TypeVisitor): TReturn */
    private readonly \Closure $namedClassString;

    /** @var \Closure(Type<object>, non-empty-string, list<Type>, TypeVisitor): TReturn */
    private readonly \Closure $namedObject;

    /** @var \Closure(Type<never>, TypeVisitor): TReturn */
    private readonly \Closure $never;

    /** @var \Closure(Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $nonEmpty;

    /** @var \Closure(Type<null>, TypeVisitor): TReturn */
    private readonly \Closure $null;

    /** @var \Closure(Type<numeric-string>, TypeVisitor): TReturn */
    private readonly \Closure $numericString;

    /** @var \Closure(Type<object>, TypeVisitor): TReturn */
    private readonly \Closure $object;

    /** @var \Closure(Type<object>, array<string, Property>, TypeVisitor): TReturn */
    private readonly \Closure $objectShape;

    /** @var \Closure(Type, Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $offset;

    /** @var \Closure(Type<resource>, TypeVisitor): TReturn */
    private readonly \Closure $resource;

    /** @var \Closure(Type<string>, TypeVisitor): TReturn */
    private readonly \Closure $string;

    /** @var \Closure(Type, non-empty-string, AtFunction|AtClass|AtMethod, Type, TypeVisitor): TReturn */
    private readonly \Closure $template;

    /** @var \Closure(Type<truthy-string>, TypeVisitor): TReturn */
    private readonly \Closure $truthyString;

    /** @var \Closure(Type, non-empty-list<Type>, TypeVisitor): TReturn */
    private readonly \Closure $union;

    /** @var \Closure(Type, Type, TypeVisitor): TReturn */
    private readonly \Closure $value;

    /** @var \Closure(Type, Type, Variance, TypeVisitor): TReturn */
    private readonly \Closure $varianceAware;

    /** @var \Closure(Type<void>, TypeVisitor): TReturn */
    private readonly \Closure $void;

    /**
     * @param ?\Closure(Type, non-empty-string, non-empty-string, TypeVisitor): TReturn $alias
     * @param ?\Closure(Type, Type, TypeVisitor): TReturn $anyLiteral
     * @param ?\Closure(Type<array<mixed>>, Type, Type, TypeVisitor): TReturn $array
     * @param ?\Closure(Type<array<mixed>>, array<ArrayElement>, bool, TypeVisitor): TReturn $arrayShape
     * @param ?\Closure(Type<bool>, TypeVisitor): TReturn $bool
     * @param ?\Closure(Type<callable>, list<Parameter>, Type, TypeVisitor): TReturn $callable
     * @param ?\Closure(Type, Type, non-empty-string, TypeVisitor): TReturn $classConstant
     * @param ?\Closure(Type<class-string>, TypeVisitor): TReturn $classString
     * @param ?\Closure(Type<non-empty-string>, non-empty-string, TypeVisitor): TReturn $classStringLiteral
     * @param ?\Closure(Type<\Closure>, list<Parameter>, Type, TypeVisitor): TReturn $closure
     * @param ?\Closure(Type, Argument|Type, Type, Type, Type, TypeVisitor): TReturn $conditional
     * @param ?\Closure(Type, non-empty-string, TypeVisitor): TReturn $constant
     * @param ?\Closure(Type<float>, TypeVisitor): TReturn $float
     * @param ?\Closure(Type<int>, TypeVisitor): TReturn $int
     * @param ?\Closure(Type, non-empty-list<Type>, TypeVisitor): TReturn $intersection
     * @param ?\Closure(Type<int>, Type, TypeVisitor): TReturn $intMask
     * @param ?\Closure(Type<int>, ?int, ?int, TypeVisitor): TReturn $intRange
     * @param ?\Closure(Type<iterable<mixed>>, Type, Type, TypeVisitor): TReturn $iterable
     * @param ?\Closure(Type, Type, TypeVisitor): TReturn $key
     * @param ?\Closure(Type<list<mixed>>, Type, TypeVisitor): TReturn $list
     * @param ?\Closure(Type, string|int|float|bool, TypeVisitor): TReturn $literal
     * @param ?\Closure(Type, TypeVisitor): TReturn $mixed
     * @param ?\Closure(Type<non-empty-string>, Type, TypeVisitor): TReturn $namedClassString
     * @param ?\Closure(Type<object>, non-empty-string, list<Type>, TypeVisitor): TReturn $namedObject
     * @param ?\Closure(Type<never>, TypeVisitor): TReturn $never
     * @param ?\Closure(Type, Type, TypeVisitor): TReturn $nonEmpty
     * @param ?\Closure(Type<null>, TypeVisitor): TReturn $null
     * @param ?\Closure(Type<numeric-string>, TypeVisitor): TReturn $numericString
     * @param ?\Closure(Type<object>, TypeVisitor): TReturn $object
     * @param ?\Closure(Type<object>, array<string, Property>, TypeVisitor): TReturn $objectShape
     * @param ?\Closure(Type, Type, Type, TypeVisitor): TReturn $offset
     * @param ?\Closure(Type<resource>, TypeVisitor): TReturn $resource
     * @param ?\Closure(Type<string>, TypeVisitor): TReturn $string
     * @param ?\Closure(Type, non-empty-string, AtFunction|AtClass|AtMethod, Type, TypeVisitor): TReturn $template
     * @param ?\Closure(Type<truthy-string>, TypeVisitor): TReturn $truthyString
     * @param ?\Closure(Type, non-empty-list<Type>, TypeVisitor): TReturn $union
     * @param ?\Closure(Type, Type, TypeVisitor): TReturn $value
     * @param ?\Closure(Type, Type, Variance, TypeVisitor): TReturn $varianceAware
     * @param ?\Closure(Type<void>, TypeVisitor): TReturn $void
     * @param ?\Closure(): TReturn $default
     */
    public function __construct(
        ?\Closure $alias = null,
        ?\Closure $anyLiteral = null,
        ?\Closure $array = null,
        ?\Closure $arrayShape = null,
        ?\Closure $bool = null,
        ?\Closure $callable = null,
        ?\Closure $classConstant = null,
        ?\Closure $classString = null,
        ?\Closure $classStringLiteral = null,
        ?\Closure $closure = null,
        ?\Closure $conditional = null,
        ?\Closure $constant = null,
        ?\Closure $float = null,
        ?\Closure $int = null,
        ?\Closure $intersection = null,
        ?\Closure $intMask = null,
        ?\Closure $intRange = null,
        ?\Closure $iterable = null,
        ?\Closure $key = null,
        ?\Closure $list = null,
        ?\Closure $literal = null,
        ?\Closure $mixed = null,
        ?\Closure $namedClassString = null,
        ?\Closure $namedObject = null,
        ?\Closure $never = null,
        ?\Closure $nonEmpty = null,
        ?\Closure $null = null,
        ?\Closure $numericString = null,
        ?\Closure $object = null,
        ?\Closure $objectShape = null,
        ?\Closure $offset = null,
        ?\Closure $resource = null,
        ?\Closure $string = null,
        ?\Closure $template = null,
        ?\Closure $truthyString = null,
        ?\Closure $union = null,
        ?\Closure $value = null,
        ?\Closure $varianceAware = null,
        ?\Closure $void = null,
        ?\Closure $default = null,
    ) {
        $this->alias = $alias ?? $default ?? throw new \InvalidArgumentException('Either $alias or $default must be provided.');
        $this->anyLiteral = $anyLiteral ?? $default ?? throw new \InvalidArgumentException('Either $anyLiteral or $default must be provided.');
        $this->array = $array ?? $default ?? throw new \InvalidArgumentException('Either $array or $default must be provided.');
        $this->arrayShape = $arrayShape ?? $default ?? throw new \InvalidArgumentException('Either $arrayShape or $default must be provided.');
        $this->bool = $bool ?? $default ?? throw new \InvalidArgumentException('Either $bool or $default must be provided.');
        $this->callable = $callable ?? $default ?? throw new \InvalidArgumentException('Either $callable or $default must be provided.');
        $this->classConstant = $classConstant ?? $default ?? throw new \InvalidArgumentException('Either $classConstant or $default must be provided.');
        $this->classString = $classString ?? $default ?? throw new \InvalidArgumentException('Either $classString or $default must be provided.');
        $this->classStringLiteral = $classStringLiteral ?? $default ?? throw new \InvalidArgumentException('Either $classStringLiteral or $default must be provided.');
        $this->closure = $closure ?? $default ?? throw new \InvalidArgumentException('Either $closure or $default must be provided.');
        $this->conditional = $conditional ?? $default ?? throw new \InvalidArgumentException('Either $conditional or $default must be provided.');
        $this->constant = $constant ?? $default ?? throw new \InvalidArgumentException('Either $constant or $default must be provided.');
        $this->float = $float ?? $default ?? throw new \InvalidArgumentException('Either $float or $default must be provided.');
        $this->int = $int ?? $default ?? throw new \InvalidArgumentException('Either $int or $default must be provided.');
        $this->intersection = $intersection ?? $default ?? throw new \InvalidArgumentException('Either $intersection or $default must be provided.');
        $this->intMask = $intMask ?? $default ?? throw new \InvalidArgumentException('Either $intMask or $default must be provided.');
        $this->intRange = $intRange ?? $default ?? throw new \InvalidArgumentException('Either $intRange or $default must be provided.');
        $this->iterable = $iterable ?? $default ?? throw new \InvalidArgumentException('Either $iterable or $default must be provided.');
        $this->key = $key ?? $default ?? throw new \InvalidArgumentException('Either $key or $default must be provided.');
        $this->list = $list ?? $default ?? throw new \InvalidArgumentException('Either $list or $default must be provided.');
        $this->literal = $literal ?? $default ?? throw new \InvalidArgumentException('Either $literal or $default must be provided.');
        $this->mixed = $mixed ?? $default ?? throw new \InvalidArgumentException('Either $mixed or $default must be provided.');
        $this->namedClassString = $namedClassString ?? $default ?? throw new \InvalidArgumentException('Either $namedClassString or $default must be provided.');
        $this->namedObject = $namedObject ?? $default ?? throw new \InvalidArgumentException('Either $namedObject or $default must be provided.');
        $this->never = $never ?? $default ?? throw new \InvalidArgumentException('Either $never or $default must be provided.');
        $this->nonEmpty = $nonEmpty ?? $default ?? throw new \InvalidArgumentException('Either $nonEmpty or $default must be provided.');
        $this->null = $null ?? $default ?? throw new \InvalidArgumentException('Either $null or $default must be provided.');
        $this->numericString = $numericString ?? $default ?? throw new \InvalidArgumentException('Either $numericString or $default must be provided.');
        $this->object = $object ?? $default ?? throw new \InvalidArgumentException('Either $object or $default must be provided.');
        $this->objectShape = $objectShape ?? $default ?? throw new \InvalidArgumentException('Either $objectShape or $default must be provided.');
        $this->offset = $offset ?? $default ?? throw new \InvalidArgumentException('Either $offset or $default must be provided.');
        $this->resource = $resource ?? $default ?? throw new \InvalidArgumentException('Either $resource or $default must be provided.');
        $this->string = $string ?? $default ?? throw new \InvalidArgumentException('Either $string or $default must be provided.');
        $this->template = $template ?? $default ?? throw new \InvalidArgumentException('Either $template or $default must be provided.');
        $this->truthyString = $truthyString ?? $default ?? throw new \InvalidArgumentException('Either $truthyString or $default must be provided.');
        $this->union = $union ?? $default ?? throw new \InvalidArgumentException('Either $union or $default must be provided.');
        $this->value = $value ?? $default ?? throw new \InvalidArgumentException('Either $value or $default must be provided.');
        $this->varianceAware = $varianceAware ?? $default ?? throw new \InvalidArgumentException('Either $varianceAware or $default must be provided.');
        $this->void = $void ?? $default ?? throw new \InvalidArgumentException('Either $void or $default must be provided.');
    }

    public function alias(Type $type, string $class, string $name): mixed
    {
        return $this->alias->__invoke($type, $class, $name, $this);
    }

    public function anyLiteral(Type $type, Type $innerType): mixed
    {
        return $this->anyLiteral->__invoke($type, $innerType, $this);
    }

    public function array(Type $type, Type $keyType, Type $valueType): mixed
    {
        return $this->array->__invoke($type, $keyType, $valueType, $this);
    }

    public function arrayShape(Type $type, array $elements, bool $sealed): mixed
    {
        return $this->arrayShape->__invoke($type, $elements, $sealed, $this);
    }

    public function bool(Type $type): mixed
    {
        return $this->bool->__invoke($type, $this);
    }

    public function callable(Type $type, array $parameters, Type $returnType): mixed
    {
        return $this->callable->__invoke($type, $parameters, $returnType, $this);
    }

    public function classConstant(Type $type, Type $classType, string $name): mixed
    {
        return $this->classConstant->__invoke($type, $classType, $name, $this);
    }

    public function classString(Type $type): mixed
    {
        return $this->classString->__invoke($type, $this);
    }

    public function classStringLiteral(Type $type, string $class): mixed
    {
        return $this->classStringLiteral->__invoke($type, $class, $this);
    }

    public function closure(Type $type, array $parameters, Type $returnType): mixed
    {
        return $this->closure->__invoke($type, $parameters, $returnType, $this);
    }

    public function conditional(Type $type, Argument|Type $subject, Type $if, Type $then, Type $else): mixed
    {
        return $this->conditional->__invoke($type, $subject, $if, $then, $else, $this);
    }

    public function constant(Type $type, string $name): mixed
    {
        return $this->constant->__invoke($type, $name, $this);
    }

    public function float(Type $type): mixed
    {
        return $this->float->__invoke($type, $this);
    }

    public function int(Type $type): mixed
    {
        return $this->int->__invoke($type, $this);
    }

    public function intersection(Type $type, array $types): mixed
    {
        return $this->intersection->__invoke($type, $types, $this);
    }

    public function intMask(Type $type, Type $innerType): mixed
    {
        return $this->intMask->__invoke($type, $innerType, $this);
    }

    public function intRange(Type $type, ?int $min, ?int $max): mixed
    {
        return $this->intRange->__invoke($type, $min, $max, $this);
    }

    public function iterable(Type $type, Type $keyType, Type $valueType): mixed
    {
        return $this->iterable->__invoke($type, $keyType, $valueType, $this);
    }

    public function key(Type $type, Type $innerType): mixed
    {
        return $this->key->__invoke($type, $innerType, $this);
    }

    public function list(Type $type, Type $valueType): mixed
    {
        return $this->list->__invoke($type, $valueType, $this);
    }

    public function literal(Type $type, string|int|float|bool $value): mixed
    {
        return $this->literal->__invoke($type, $value, $this);
    }

    public function mixed(Type $type): mixed
    {
        return $this->mixed->__invoke($type, $this);
    }

    public function namedClassString(Type $type, Type $objectType): mixed
    {
        return $this->namedClassString->__invoke($type, $objectType, $this);
    }

    public function namedObject(Type $type, string $class, array $templateArguments): mixed
    {
        return $this->namedObject->__invoke($type, $class, $templateArguments, $this);
    }

    public function never(Type $type): mixed
    {
        return $this->never->__invoke($type, $this);
    }

    public function nonEmpty(Type $type, Type $innerType): mixed
    {
        return $this->nonEmpty->__invoke($type, $innerType, $this);
    }

    public function null(Type $type): mixed
    {
        return $this->null->__invoke($type, $this);
    }

    public function numericString(Type $type): mixed
    {
        return $this->numericString->__invoke($type, $this);
    }

    public function object(Type $type): mixed
    {
        return $this->object->__invoke($type, $this);
    }

    public function objectShape(Type $type, array $properties): mixed
    {
        return $this->objectShape->__invoke($type, $properties, $this);
    }

    public function offset(Type $type, Type $innerType, Type $offset): mixed
    {
        return $this->offset->__invoke($type, $innerType, $offset, $this);
    }

    public function resource(Type $type): mixed
    {
        return $this->resource->__invoke($type, $this);
    }

    public function string(Type $type): mixed
    {
        return $this->string->__invoke($type, $this);
    }

    public function template(Type $type, string $name, AtFunction|AtClass|AtMethod $declaredAt, Type $constraint): mixed
    {
        return $this->template->__invoke($type, $name, $declaredAt, $constraint, $this);
    }

    public function truthyString(Type $type): mixed
    {
        return $this->truthyString->__invoke($type, $this);
    }

    public function union(Type $type, array $types): mixed
    {
        return $this->union->__invoke($type, $types, $this);
    }

    public function value(Type $type, Type $innerType): mixed
    {
        return $this->value->__invoke($type, $innerType, $this);
    }

    public function varianceAware(Type $type, Type $innerType, Variance $variance): mixed
    {
        return $this->varianceAware->__invoke($type, $innerType, $variance, $this);
    }

    public function void(Type $type): mixed
    {
        return $this->void->__invoke($type, $this);
    }
}
