<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type;
use Typhoon\Type\ArrayBareT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableBareT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\ConstantMaskT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableBareT;
use Typhoon\Type\IterableT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NegativeIntT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonNegativeIntT;
use Typhoon\Type\NonPositiveIntT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectShapeT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\Parameter;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\Property;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

/**
 * @api
 * @implements Visitor<non-empty-string>
 */
final readonly class Stringify implements Visitor
{
    /**
     * @param ?Visitor<non-empty-string> $next
     */
    public function __construct(
        private ?Visitor $next = null,
    ) {}

    /**
     * @return non-empty-string
     */
    public function safe(Type $type): string
    {
        return $type->accept($this->next ?? $this);
    }

    /**
     * @return non-empty-string
     */
    public function unsafe(Type $type): string
    {
        $string = $type->accept($this->next ?? $this);
        $offset = 0;

        while ($string[$offset] === '(') {
            ++$offset;
        }

        if ($offset === 0) {
            return $string;
        }

        /** @phpstan-ignore return.type */
        return substr($string, $offset, -$offset);
    }

    #[\Override]
    public function neverT(NeverT $type): string
    {
        return 'never';
    }

    #[\Override]
    public function voidT(VoidT $type): string
    {
        return 'void';
    }

    #[\Override]
    public function nullT(NullT $type): string
    {
        return 'null';
    }

    #[\Override]
    public function falseT(FalseT $type): string
    {
        return 'false';
    }

    #[\Override]
    public function trueT(TrueT $type): string
    {
        return 'true';
    }

    #[\Override]
    public function boolT(BoolT $type): string
    {
        return 'bool';
    }

    #[\Override]
    public function intT(IntT $type): string
    {
        return 'int';
    }

    #[\Override]
    public function intValueT(IntValueT $type): string
    {
        return (string) $type->value;
    }

    #[\Override]
    public function intRangeT(IntRangeT $type): string
    {
        return \sprintf('int<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
    }

    #[\Override]
    public function negativeIntT(NegativeIntT $type): string
    {
        return 'negative-int';
    }

    #[\Override]
    public function nonPositiveIntT(NonPositiveIntT $type): string
    {
        return 'non-positive-int';
    }

    #[\Override]
    public function nonZeroIntT(NonZeroIntT $type): string
    {
        return 'non-zero-int';
    }

    #[\Override]
    public function nonNegativeIntT(NonNegativeIntT $type): string
    {
        return 'non-negative-int';
    }

    #[\Override]
    public function positiveIntT(PositiveIntT $type): string
    {
        return 'positive-int';
    }

    #[\Override]
    public function bitmaskT(BitmaskT $type): string
    {
        return \sprintf('int-mask-of<%s>', $this->unsafe($type->intType));
    }

    #[\Override]
    public function floatT(FloatT $type): string
    {
        return 'float';
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): string
    {
        $string = $this->floatToString($type->value);

        return match ($string) {
            'NAN', 'INF', '-INF' => $string,
            default => $string . (str_contains($string, '.') ? '' : '.0'),
        };
    }

    #[\Override]
    public function floatRangeT(FloatRangeT $type): string
    {
        return \sprintf(
            'float<%s, %s>',
            $type->min === null ? 'min' : $this->floatToString($type->min),
            $type->max === null ? 'max' : $this->floatToString($type->max),
        );
    }

    #[\Override]
    public function stringT(StringT $type): string
    {
        return 'string';
    }

    #[\Override]
    public function nonEmptyStringT(NonEmptyStringT $type): string
    {
        return 'non-empty-string';
    }

    #[\Override]
    public function truthyStringT(TruthyStringT $type): string
    {
        return 'truthy-string';
    }

    #[\Override]
    public function numericStringT(NumericStringT $type): string
    {
        return 'numeric-string';
    }

    #[\Override]
    public function lowercaseStringT(LowercaseStringT $type): string
    {
        return 'lowercase-string';
    }

    #[\Override]
    public function stringValueT(StringValueT $type): string
    {
        /** @var non-empty-string */
        return str_replace("\n", '\n', var_export($type->value, return: true));
    }

    #[\Override]
    public function classT(ClassT $type): string
    {
        return \sprintf('class-string<%s>', $this->unsafe($type->objectType));
    }

    #[\Override]
    public function arrayKeyT(ArrayKeyT $type): string
    {
        return 'array-key';
    }

    #[\Override]
    public function numericT(NumericT $type): string
    {
        return 'numeric';
    }

    #[\Override]
    public function scalarT(ScalarT $type): string
    {
        return 'scalar';
    }

    #[\Override]
    public function listT(ListT $type): string
    {
        $value = $this->unsafe($type->valueType);

        $elements = implode(', ', array_map($this->unsafe(...), $type->elementTypes));

        if ($value === 'never') {
            return \sprintf('list{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-list' : 'list';

        $unsealed = \sprintf('<%s>', $value);

        if ($elements === '') {
            return $name . $unsealed;
        }

        return \sprintf('%s{%s, ...%s}', $name, $elements, $unsealed === '<mixed>' ? '' : $unsealed);
    }

    #[\Override]
    public function arrayBareT(ArrayBareT $type): string
    {
        return 'array';
    }

    #[\Override]
    public function arrayT(ArrayT $type): string
    {
        $value = $this->unsafe($type->valueType);

        $elements = implode(', ', array_map($this->arrayElement(...), $type->elements));

        if ($value === 'never') {
            return \sprintf('array{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-array' : 'array';

        $key = $this->unsafe($type->keyType);

        $unsealed = match ($key) {
            'array-key', 'int|string', 'string|int' => \sprintf('<%s>', $value),
            default => \sprintf('<%s, %s>', $key, $value),
        };

        if ($elements === '') {
            return $name . $unsealed;
        }

        return \sprintf('%s{%s, ...%s}', $name, $elements, $unsealed === '<mixed>' ? '' : $unsealed);
    }

    #[\Override]
    public function objectT(ObjectT $type): string
    {
        return 'object';
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): string
    {
        if ($type->templateArguments === []) {
            return $type->class;
        }

        return \sprintf('%s<%s>', $type->class, implode(', ', array_map($this->unsafe(...), $type->templateArguments)));
    }

    #[\Override]
    public function objectShapeT(ObjectShapeT $type): string
    {
        return \sprintf('object{%s}', implode(', ', array_map($this->property(...), $type->properties)));
    }

    #[\Override]
    public function iterableBareT(IterableBareT $type): string
    {
        return 'iterable';
    }

    #[\Override]
    public function iterableT(IterableT $type): string
    {
        $key = $this->unsafe($type->keyType);
        $value = $this->unsafe($type->valueType);

        if ($key === 'mixed') {
            return \sprintf('iterable<%s>', $value);
        }

        return \sprintf('iterable<%s, %s>', $key, $value);
    }

    #[\Override]
    public function callableBareT(CallableBareT $type): string
    {
        return 'callable';
    }

    #[\Override]
    public function callableT(CallableT|ClosureT $type): string
    {
        return \sprintf(
            '(%s(%s): %s)',
            $type instanceof CallableT ? 'callable' : 'Closure',
            implode(', ', array_map($this->parameter(...), $type->parameters)),
            $this->safe($type->returnType),
        );
    }

    #[\Override]
    public function closureT(ClosureT $type): string
    {
        return $this->callableT($type);
    }

    #[\Override]
    public function resourceT(ResourceT $type): string
    {
        return 'resource';
    }

    #[\Override]
    public function intersectionT(IntersectionT $type): string
    {
        return \sprintf('(%s)', implode('&', array_map($this->safe(...), $type->types)));
    }

    #[\Override]
    public function unionT(UnionT $type): string
    {
        return \sprintf('(%s)', implode('|', array_map($this->safe(...), $type->types)));
    }

    #[\Override]
    public function constantT(ConstantT $type): string
    {
        return \sprintf('const<%s>', $type->name);
    }

    #[\Override]
    public function constantMaskT(ConstantMaskT $type): mixed
    {
        return \sprintf('const<%s>', $type->mask->mask);
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): string
    {
        return \sprintf('%s::%s', $type->class, $type->name);
    }

    #[\Override]
    public function classConstantMaskT(ClassConstantMaskT $type): string
    {
        return \sprintf('%s::%s', $type->class, $type->mask->mask);
    }

    #[\Override]
    public function mixedT(MixedT $type): string
    {
        return 'mixed';
    }

    /**
     * @return non-empty-string
     */
    public function floatToString(float $float): string
    {
        if ($float === NAN) {
            return 'NAN';
        }

        $string = (string) $float;

        if (!preg_match('/\.(\d++)[eE]([+-])(\d++)/', $string, $matches)) {
            return $string;
        }

        if ($matches[2] === '+') {
            return number_format($float, thousands_separator: '');
        }

        $decimals = (int) $matches[3];

        if ($matches[1] !== '0') {
            $decimals += \strlen($matches[1]);
        }

        return number_format($float, $decimals, thousands_separator: '');
    }

    /**
     * @return non-empty-string
     */
    public function arrayElement(ArrayElement $element): string
    {
        return \sprintf(
            '%s%s: %s',
            $this->safe(\is_int($element->key) ? new IntValueT($element->key) : new StringValueT($element->key)),
            $element->isOptional ? '?' : '',
            $this->unsafe($element->type),
        );
    }

    /**
     * @return non-empty-string
     */
    public function property(Property $property): string
    {
        return \sprintf(
            '%s%s: %s',
            $property->name,
            $property->isOptional ? '?' : '',
            $this->unsafe($property->type),
        );
    }

    /**
     * @return non-empty-string
     */
    public function parameter(Parameter $parameter): string
    {
        $string = $this->safe($parameter->type);

        if ($parameter->name !== null) {
            $string .= ' ';
        }

        if ($parameter->isPassedByReference) {
            $string .= '&';
        }

        if ($parameter->isVariadic) {
            $string .= '...';
        }

        if ($parameter->name !== null) {
            $string .= '$' . $parameter->name;
        }

        if ($parameter->hasDefault) {
            $string .= '=';
        }

        return $string;
    }
}
