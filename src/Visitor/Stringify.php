<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayDefaultT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableDefaultT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntMaskT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IsSupertypeT;
use Typhoon\Type\IterableDefaultT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyT;
use Typhoon\Type\ListT;
use Typhoon\Type\LiteralT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectDefaultT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\ParentDefaultT;
use Typhoon\Type\ParentT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\Property;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\SelfDefaultT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticDefaultT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\SuperClass;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueT;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

/**
 * @api
 * @implements Visitor<non-empty-string>
 */
abstract class Stringify implements Visitor
{
    /** @var ?\SplObjectStorage<TemplateT, non-negative-int> */
    private ?\SplObjectStorage $templates = null;

    /**
     * @param array<ArrayElement> $elements
     */
    private function arrayElements(array $elements): string
    {
        if (array_is_list($elements) && array_all($elements, static fn(ArrayElement $e) => !$e->isOptional)) {
            return implode(', ', array_map(
                fn(ArrayElement $element): string => $element->type->accept($this),
                $elements,
            ));
        }

        return implode(', ', array_map(
            fn(int|string $key, ArrayElement $element): string => \sprintf(
                '%s%s: %s',
                \is_int($key) ? $key : $this->stringValue(new StringValueT($key)),
                $element->isOptional ? '?' : '',
                $element->type->accept($this),
            ),
            array_keys($elements),
            $elements,
        ));
    }

    /**
     * @return non-empty-string
     */
    private function property(Property $property): string
    {
        return \sprintf('%s%s: %s', $property->name, $property->isOptional ? '?' : '', $property->type->accept($this));
    }

    /**
     * @param non-empty-string $name
     * @param list<Type> $templateArguments
     * @return non-empty-string
     */
    private function constructor(string $name, array $templateArguments): string
    {
        if ($templateArguments === []) {
            return $name;
        }

        return \sprintf('%s<%s>', $name, implode(', ', array_map(
            fn(Type $type): string => $type->accept($this),
            $templateArguments,
        )));
    }

    public function never(NeverT $type): string
    {
        return 'never';
    }

    public function void(VoidT $type): string
    {
        return 'void';
    }

    public function null(NullT $type): string
    {
        return 'null';
    }

    public function false(FalseT $type): string
    {
        return 'false';
    }

    public function true(TrueT $type): string
    {
        return 'true';
    }

    public function bool(BoolT $type): string
    {
        return 'bool';
    }

    public function int(IntT $type): string
    {
        return 'int';
    }

    public function intValue(IntValueT $type): string
    {
        return (string) $type->value;
    }

    public function intRange(IntRangeT $type): string
    {
        return \sprintf('int<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function negativeInt(NegativeIntT $type): string
    {
        return 'negative-int';
    }

    public function nonPositiveInt(NonPositiveIntT $type): string
    {
        return 'non-positive-int';
    }

    public function nonZeroInt(NonZeroIntT $type): string
    {
        return 'non-zero-int';
    }

    public function nonNegativeInt(NonNegativeIntT $type): string
    {
        return 'non-negative-int';
    }

    public function positiveInt(PositiveIntT $type): string
    {
        return 'positive-int';
    }

    public function intMask(IntMaskT $type): string
    {
        return \sprintf('int-mask-of<%s>', $type->ints->accept($this));
    }

    public function float(FloatT $type): string
    {
        return 'float';
    }

    public function floatValue(FloatValueT $type): string
    {
        return $type->value;
    }

    public function floatRange(FloatRangeT $type): string
    {
        return \sprintf('float<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function string(StringT $type): string
    {
        return 'string';
    }

    public function nonEmptyString(NonEmptyStringT $type): string
    {
        return 'non-empty-string';
    }

    public function truthyString(TruthyStringT $type): string
    {
        return 'truthy-string';
    }

    public function numericString(NumericStringT $type): string
    {
        return 'numeric-string';
    }

    public function lowercaseString(LowercaseStringT $type): string
    {
        return 'lowercase-string';
    }

    public function stringValue(StringValueT $type): string
    {
        /** @var non-empty-string */
        return str_replace("\n", '\n', var_export($type->value, return: true));
    }

    public function class(ClassT $type): string
    {
        return \sprintf('class-string<%s>', $type->object->accept($this));
    }

    public function arrayKey(ArrayKeyT $type): string
    {
        return 'array-key';
    }

    public function numeric(NumericT $type): string
    {
        return 'numeric';
    }

    public function scalar(ScalarT $type): string
    {
        return 'scalar';
    }

    public function list(ListT $type): string
    {
        $value = $type->value->accept($this);

        $elements = $this->arrayElements($type->elements);

        if ($value === 'never') {
            return \sprintf('list{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-list' : 'list';

        $unsealed = $value === 'mixed' ? '' : \sprintf('<%s>', $value);

        if ($elements === '') {
            return $name . $unsealed;
        }

        return \sprintf('%s{%s, ...%s}', $name, $elements, $unsealed);
    }

    public function arrayDefault(ArrayDefaultT $type): string
    {
        return 'array';
    }

    public function array(ArrayT $type): string
    {
        $value = $type->value->accept($this);

        $elements = $this->arrayElements($type->elements);

        if ($value === 'never') {
            return \sprintf('array{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-array' : 'array';

        $key = $type->key->accept($this);

        $unsealed = match ($key) {
            'array-key', 'int|string', 'string|int' => $value === 'mixed' ? '' : \sprintf('<%s>', $value),
            default => \sprintf('<%s, %s>', $key, $value),
        };

        if ($elements === '') {
            return $name . $unsealed;
        }

        return \sprintf('%s{%s, ...%s}', $name, $elements, $unsealed);
    }

    public function iterableDefault(IterableDefaultT $type): string
    {
        return 'iterable';
    }

    public function iterable(IterableT $type): string
    {
        $key = $type->key->accept($this);
        $value = $type->value->accept($this);

        if ($key === 'mixed') {
            if ($value === 'mixed') {
                return 'iterable';
            }

            return \sprintf('iterable<%s>', $value);
        }

        return \sprintf('iterable<%s, %s>', $key, $value);
    }

    public function objectDefault(ObjectDefaultT $type): string
    {
        return 'object';
    }

    public function object(ObjectT $type): string
    {
        $superClasses = array_map(
            fn(SuperClass $class): string => $this->constructor($class->class, $class->templateArguments),
            $type->superClasses,
        );

        if ($superClasses !== [] && $type->templates === [] && $type->properties === []) {
            return implode('&', $superClasses);
        }

        // todo templates & super classes
        return \sprintf('object{%s}', implode(', ', array_map($this->property(...), $type->properties)));
    }

    public function selfDefault(SelfDefaultT $type): mixed
    {
        return 'self';
    }

    public function self(SelfT $type): string
    {
        return $this->constructor('self', $type->templateArguments);
    }

    public function parentDefault(ParentDefaultT $type): mixed
    {
        return 'parent';
    }

    public function parent(ParentT $type): string
    {
        return $this->constructor('parent', $type->templateArguments);
    }

    public function staticDefault(StaticDefaultT $type): mixed
    {
        return 'static';
    }

    public function static(StaticT $type): string
    {
        return $this->constructor('static', $type->templateArguments);
    }

    public function callableDefault(CallableDefaultT $type): string
    {
        return 'callable';
    }

    public function callable(CallableT $type): string
    {
        // todo
        return 'callable';
    }

    public function closure(ClosureT $type): string
    {
        // todo
        return 'closure';
    }

    public function resource(ResourceT $type): string
    {
        return 'resource';
    }

    public function literal(LiteralT $type): string
    {
        return \sprintf('literal<%s>', $type->type->accept($this));
    }

    public function intersection(IntersectionT $type): string
    {
        return implode('&', array_map(fn(Type $type): string => $type->accept($this), $type->types));
    }

    public function union(UnionT $type): string
    {
        return \sprintf('(%s)', implode('|', array_map(fn(Type $type): string => $type->accept($this), $type->types)));
    }

    public function constant(ConstantT $type): string
    {
        return \sprintf('!%s', $type->name);
    }

    public function classConstant(ClassConstantT $type): string
    {
        return \sprintf('%s::%s', $type->class->accept($this), $type->name);
    }

    public function classConstantMask(ClassConstantMaskT $type): string
    {
        return \sprintf('%s::%s*', $type->class->accept($this), $type->namePrefix);
    }

    public function key(KeyT $type): string
    {
        return \sprintf('key-of<%s>', $type->array->accept($this));
    }

    public function value(ValueT $type): string
    {
        return \sprintf('value-of<%s>', $type->array->accept($this));
    }

    public function offset(OffsetT $type): string
    {
        return \sprintf('%s[%s]', $type->array->accept($this), $type->key->accept($this));
    }

    public function isSubtype(IsSubtypeT $type): string
    {
        return \sprintf('(%s <: %s)', $type->left->accept($this), $type->right->accept($this));
    }

    public function isSupertype(IsSupertypeT $type): mixed
    {
        return \sprintf('(%s :> %s)', $type->left->accept($this), $type->right->accept($this));
    }

    public function ternary(TernaryT $type): string
    {
        return \sprintf('(%s ? %s : %s)', $type->condition->accept($this), $type->then->accept($this), $type->else->accept($this));
    }

    public function alias(AliasT $type): string
    {
        return \sprintf('%s@%s', $type->class, $type->name);
    }

    public function mixed(MixedT $type): string
    {
        return 'mixed';
    }

    public function template(TemplateT $type): string
    {
        if ($this->templates === null) {
            /** @var \SplObjectStorage<TemplateT, non-negative-int> */
            $templates = new \SplObjectStorage();
            $this->templates = $templates;
        }

        return \sprintf('$T%s', $this->templates[$type] ??= $this->templates->count());
    }
}
