<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntMaskT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IsSupertypeT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyT;
use Typhoon\Type\ListT;
use Typhoon\Type\LiteralT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\ParentOpenT;
use Typhoon\Type\ParentT;
use Typhoon\Type\Property;
use Typhoon\Type\SelfOpenT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticOpenT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\SuperClass;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueT;
use Typhoon\Type\Visitor;

/**
 * @api
 * @implements Visitor<non-empty-string>
 */
abstract class Stringify implements Visitor
{
    public function intValue(IntValueT $type): string
    {
        return (string) $type->value;
    }

    public function intRange(IntRangeT $type): string
    {
        return \sprintf('int<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function intMask(IntMaskT $type): string
    {
        return \sprintf('int-mask-of<%s>', $type->ints->accept($this));
    }

    public function floatValue(FloatValueT $type): string
    {
        return $type->value;
    }

    public function floatRange(FloatRangeT $type): string
    {
        return \sprintf('float<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
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

    /**
     * @return non-empty-string
     */
    private function property(Property $property): string
    {
        return \sprintf('%s%s: %s', $property->name, $property->isOptional ? '?' : '', $property->type->accept($this));
    }

    public function selfOpen(SelfOpenT $type): mixed
    {
        return 'self';
    }

    public function self(SelfT $type): string
    {
        return $this->constructor('self', $type->templateArguments);
    }

    public function parentOpen(ParentOpenT $type): mixed
    {
        return 'parent';
    }

    public function parent(ParentT $type): string
    {
        return $this->constructor('parent', $type->templateArguments);
    }

    public function staticOpen(StaticOpenT $type): mixed
    {
        return 'static';
    }

    public function static(StaticT $type): string
    {
        return $this->constructor('static', $type->templateArguments);
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

    public function alias(AliasT $type): string
    {
        return \sprintf('%s@%s', $type->class, $type->name);
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

    /**
     * @var ?\SplObjectStorage<TemplateT, non-negative-int>
     */
    private ?\SplObjectStorage $templates = null;

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
