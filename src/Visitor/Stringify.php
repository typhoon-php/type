<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassStringT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntMaskOfT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyOfT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NativeArrayT;
use Typhoon\Type\NativeIterableT;
use Typhoon\Type\NativeObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\Parameter;
use Typhoon\Type\ParentT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\Property;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\SelfT;
use Typhoon\Type\Shortcut;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

/**
 * This class is partially generated, be careful when editing it.
 *
 * @api
 * @implements Visitor<non-empty-string>
 */
abstract class Stringify implements Visitor
{
    /** @var ?\SplObjectStorage<TemplateT, non-negative-int> */
    private ?\SplObjectStorage $templates = null;

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

    public function intRange(IntRangeT $type): string
    {
        return \sprintf('int<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    public function intMaskOf(IntMaskOfT $type): string
    {
        return \sprintf('int-mask-of<%s>', $type->of->accept($this));
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
        return $this->escapeStringLiteral($type->value);
    }

    public function classString(ClassStringT $type): string
    {
        return \sprintf('class-string<%s>', $type->of->accept($this));
    }

    public function list(ListT $type): string
    {
        if ($type->elements === []) {
            return $this->constructor($type->isNonEmpty ? 'non-empty-list' : 'list', [$type->value]);
        }

        $elements = array_map(
            $this->arrayElement(...),
            array_keys($type->elements),
            $type->elements,
        );

        $unsealed = $this->constructor('...', [$type->value]);

        // todo via dereference()
        if ($unsealed === '...<mixed>') {
            $elements[] = '...';
        } elseif ($unsealed !== '...<never>') {
            $elements[] = $unsealed;
        }

        return \sprintf('%slist{%s}', $type->isNonEmpty ? 'non-empty' : '', implode(', ', $elements));
    }

    public function array(ArrayT $type): string
    {
        if ($type->elements === []) {
            return $this->constructor($type->isNonEmpty ? 'non-empty-array' : 'array', [$type->key, $type->value]);
        }

        $elements = array_map(
            $this->arrayElement(...),
            array_keys($type->elements),
            $type->elements,
        );

        $unsealed = $this->constructor('...', [$type->key, $type->value]);

        // todo via dereference()
        if ($unsealed === '...<array-key, mixed>') {
            $elements[] = '...';
        } elseif (!str_ends_with($unsealed, 'never>')) {
            $elements[] = $unsealed;
        }

        return \sprintf('%sarray{%s}', $type->isNonEmpty ? 'non-empty' : '', implode(', ', $elements));
    }

    public function keyOf(KeyOfT $type): string
    {
        return \sprintf('key-of<%s>', $type->of->accept($this));
    }

    public function offset(OffsetT $type): string
    {
        return \sprintf('%s[%s]', $type->value->accept($this), $type->key->accept($this));
    }

    public function object(ObjectT $type): string
    {
        return \sprintf('object{%s}', implode(', ', array_map($this->property(...), $type->properties)));
    }

    public function self(SelfT $type): string
    {
        return $this->constructor('self', $type->templateArguments);
    }

    public function parent(ParentT $type): string
    {
        return $this->constructor('parent', $type->templateArguments);
    }

    public function static(StaticT $type): string
    {
        return $this->constructor('static', $type->templateArguments);
    }

    public function callable(CallableT $type): string
    {
        return \sprintf(
            '(callable(%s): %s)',
            implode(', ', array_map($this->parameter(...), $type->parameters)),
            $type->returns->accept($this),
        );
    }

    public function resource(ResourceT $type): string
    {
        return 'resource';
    }

    public function constant(ConstantT $type): string
    {
        return \sprintf('!%s', $type->name);
    }

    public function classConstant(ClassConstantT $type): string
    {
        return \sprintf('%s::%s', $type->on->accept($this), $type->name);
    }

    public function classConstantMask(ClassConstantMaskT $type): string
    {
        return \sprintf('%s::%s*', $type->on->accept($this), $type->namePrefix);
    }

    public function template(TemplateT $type): string
    {
        if ($this->templates === null) {
            /** @var \SplObjectStorage<TemplateT, non-negative-int> */
            $templates = new \SplObjectStorage();
            $this->templates = $templates;
        }

        $index = $this->templates[$type] ??= \count($this->templates);

        return \sprintf('$T%d', $index);
    }

    public function alias(AliasT $type): string
    {
        return \sprintf('@%s::%s', $type->class, $type->name);
    }

    public function intersection(IntersectionT $type): string
    {
        return implode('&', array_map(fn(Type $type): string => $type->accept($this), $type->of));
    }

    public function union(UnionT $type): string
    {
        return \sprintf('(%s)', implode('|', array_map(fn(Type $type): string => $type->accept($this), $type->of)));
    }

    public function isSubtype(IsSubtypeT $type): string
    {
        return \sprintf('(%s <: %s)', $type->left->accept($this), $type->right->accept($this));
    }

    public function ternary(TernaryT $type): string
    {
        return \sprintf('(%s ? %s : %s)', $type->condition->accept($this), $type->then->accept($this), $type->else->accept($this));
    }

    public function shortcut(Shortcut $type): string
    {
        if ($type instanceof IterableT) {
            return $this->constructor('iterable', [$type->key, $type->value]);
        }

        return match ($type) {
            BoolT::T => 'bool',
            NegativeIntT::T => 'negative-int',
            NonPositiveIntT::T => 'non-positive-int',
            NonNegativeIntT::T => 'non-negative-int',
            PositiveIntT::T => 'positive-int',
            NonZeroIntT::T => 'non-zero-int',
            IntT::T => 'int',
            FloatT::T => 'float',
            ArrayKeyT::T => 'array-key',
            NumericT::T => 'numeric',
            ScalarT::T => 'scalar',
            NativeObjectT::T => 'object',
            NativeIterableT::T => 'iterable',
            NativeArrayT::T => 'array',
            MixedT::T => 'mixed',
            default => $type->dereference()->accept($this),
        };
    }

    /**
     * @return non-empty-string
     */
    private function arrayElement(int|string $key, ArrayElement $element): string
    {
        return \sprintf(
            '%s%s: %s',
            \is_int($key) ? $key : $this->escapeStringLiteral($key),
            $element->isOptional ? '?' : '',
            $element->type->accept($this),
        );
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

    /**
     * @return non-empty-string
     */
    private function parameter(Parameter $parameter): string
    {
        /** @phpstan-ignore return.type */
        return \sprintf(
            '%s%s%s%s',
            $parameter->type->accept($this),
            $parameter->byReference ? '&' : '',
            $parameter->variadic ? '...' : '',
            $parameter->hasDefault ? '=' : '',
        );
    }

    /**
     * @return non-empty-string
     */
    private function escapeStringLiteral(string $literal): string
    {
        /** @var non-empty-string */
        return str_replace("\n", '\n', var_export($literal, return: true));
    }
}
