<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayDefaultT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableDefaultT;
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
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IterableDefaultT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyOfT;
use Typhoon\Type\ListT;
use Typhoon\Type\LiteralStringT;
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
use Typhoon\Type\ObjectDefaultT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\Parameter;
use Typhoon\Type\ParentT;
use Typhoon\Type\PositiveIntT;
use Typhoon\Type\Property;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\Template;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueOfT;
use Typhoon\Type\Variance;
use Typhoon\Type\Visitor;
use Typhoon\Type\VoidT;

/**
 * @api
 * @implements Visitor<non-empty-string>
 */
abstract class Stringify implements Visitor
{
    private int $unknownTemplateIndex = 0;

    /**
     * @param \SplObjectStorage<TemplateT, non-empty-string> $templateNames
     */
    public function __construct(
        /** @phpstan-ignore parameter.defaultValue */
        protected readonly \SplObjectStorage $templateNames = new \SplObjectStorage(),
    ) {}

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
        return \sprintf('int-mask-of<%s>', $this->stringifyUnwrap($type->intType));
    }

    #[\Override]
    public function floatT(FloatT $type): string
    {
        return 'float';
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): string
    {
        $value = $type->value;

        if ($value->getScale() === 0) {
            return $value->toScale(1)->__toString();
        }

        return $value->__toString();
    }

    #[\Override]
    public function floatRangeT(FloatRangeT $type): string
    {
        return \sprintf('float<%s, %s>', $type->min?->__toString() ?? 'min', $type->max?->__toString() ?? 'max');
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
    public function literalStringT(LiteralStringT $type): mixed
    {
        return 'literal-string';
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
        return \sprintf('class-string<%s>', $this->stringifyUnwrap($type->objectType));
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
        $value = $this->stringifyUnwrap($type->valueType);

        $elements = implode(', ', array_map($this->stringifyUnwrap(...), $type->elementTypes));

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

    #[\Override]
    public function arrayDefaultT(ArrayDefaultT $type): string
    {
        return 'array';
    }

    #[\Override]
    public function arrayT(ArrayT $type): string
    {
        $value = $this->stringifyUnwrap($type->valueType);

        $elements = implode(', ', array_map($this->arrayElement(...), $type->elements));

        if ($value === 'never') {
            return \sprintf('array{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-array' : 'array';

        $key = $this->stringifyUnwrap($type->keyType);

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
     * @return non-empty-string
     */
    protected function arrayElement(ArrayElement $element): string
    {
        return \sprintf(
            '%s%s: %s',
            \is_int($element->key) ? $element->key : $this->stringValueT(new StringValueT($element->key)),
            $element->isOptional ? '?' : '',
            $this->stringifyUnwrap($element->type),
        );
    }

    #[\Override]
    public function objectDefaultT(ObjectDefaultT $type): string
    {
        return 'object';
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): string
    {
        return $this->constructor($type->class, $type->templateArguments);
    }

    #[\Override]
    public function objectT(ObjectT $type): string
    {
        return \sprintf(
            'object%s%s%s',
            $this->templates($type->templates),
            implode('', array_map(
                fn(NamedObjectT $inherited): string => ':' . $this->namedObjectT($inherited),
                $type->supertypes,
            )),
            $type->properties === [] ? '' : \sprintf('{%s}', implode(', ', array_map($this->property(...), $type->properties))),
        );
    }

    /**
     * @return non-empty-string
     */
    protected function property(Property $property): string
    {
        return \sprintf(
            '%s%s: %s',
            $property->name,
            $property->isOptional ? '?' : '',
            $this->stringifyUnwrap($property->type),
        );
    }

    #[\Override]
    public function selfT(SelfT $type): string
    {
        return $this->constructor('self', $type->templateArguments);
    }

    #[\Override]
    public function parentT(ParentT $type): string
    {
        return $this->constructor('parent', $type->templateArguments);
    }

    #[\Override]
    public function staticT(StaticT $type): string
    {
        return $this->constructor('static', $type->templateArguments);
    }

    /**
     * @param non-empty-string $name
     * @param list<Type> $templateArguments
     * @return non-empty-string
     */
    protected function constructor(string $name, array $templateArguments): string
    {
        if ($templateArguments === []) {
            return $name;
        }

        return \sprintf('%s<%s>', $name, implode(', ', array_map($this->stringifyUnwrap(...), $templateArguments)));
    }

    #[\Override]
    public function iterableDefaultT(IterableDefaultT $type): string
    {
        return 'iterable';
    }

    #[\Override]
    public function iterableT(IterableT $type): string
    {
        $key = $this->stringifyUnwrap($type->keyType);
        $value = $this->stringifyUnwrap($type->valueType);

        if ($key === 'mixed') {
            if ($value === 'mixed') {
                return 'iterable';
            }

            return \sprintf('iterable<%s>', $value);
        }

        return \sprintf('iterable<%s, %s>', $key, $value);
    }

    #[\Override]
    public function callableDefaultT(CallableDefaultT $type): string
    {
        return 'callable';
    }

    #[\Override]
    public function callableT(CallableT $type): string
    {
        return $this->callables($type);
    }

    #[\Override]
    public function closureT(ClosureT $type): string
    {
        return $this->callables($type);
    }

    /**
     * @return non-empty-string
     */
    protected function callables(CallableT|ClosureT $type): string
    {
        $prefix = $type instanceof CallableT ? 'callable' : 'Closure';

        $string = \sprintf(
            '(%s%s(%s): %s)',
            $prefix,
            $this->templates($type->templates),
            implode(', ', array_map($this->parameter(...), $type->parameters)),
            $this->stringify($type->returnType),
        );

        if ($string === "({$prefix}(): mixed)") {
            return $prefix;
        }

        return $string;
    }

    /**
     * @return non-empty-string
     */
    protected function parameter(Parameter $parameter): string
    {
        $string = $this->stringify($parameter->type);

        if ($parameter->name !== null) {
            $string .= ' ';
        }

        if ($parameter->isPassedByReference) {
            $string .= '&';

            // todo $parameter->outType
        }

        if ($parameter->isVariadic) {
            $string .= '...';
        }

        if ($parameter->name !== null) {
            $string .= '$' . $parameter->name;
        }

        if ($parameter->hasDefault) {
            $string .= '=';

            if ($parameter->defaultType !== null) {
                $string .= $this->stringify($parameter->defaultType);
            }
        }

        return $string;
    }

    #[\Override]
    public function resourceT(ResourceT $type): string
    {
        return 'resource';
    }

    #[\Override]
    public function intersectionT(IntersectionT $type): string
    {
        return \sprintf('%s', implode('&', array_map($this->stringify(...), $type->types)));
    }

    #[\Override]
    public function unionT(UnionT $type): string
    {
        return \sprintf('(%s)', implode('|', array_map($this->stringify(...), $type->types)));
    }

    #[\Override]
    public function constantT(ConstantT $type): string
    {
        return \sprintf('const<%s>', $type->name);
    }

    #[\Override]
    public function constantMaskT(ConstantMaskT $type): mixed
    {
        return \sprintf('const<%s>', $type->mask->toString());
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): string
    {
        return \sprintf('%s::%s', $type->class, $type->name);
    }

    #[\Override]
    public function classConstantMaskT(ClassConstantMaskT $type): string
    {
        return \sprintf('%s::%s', $type->class, $type->mask->toString());
    }

    #[\Override]
    public function keyOfT(KeyOfT $type): string
    {
        return \sprintf('key-of<%s>', $this->stringifyUnwrap($type->arrayType));
    }

    #[\Override]
    public function valueOfT(ValueOfT $type): string
    {
        return \sprintf('value-of<%s>', $this->stringifyUnwrap($type->arrayType));
    }

    #[\Override]
    public function offsetT(OffsetT $type): string
    {
        return \sprintf('%s[%s]', $this->stringify($type->arrayType), $this->stringifyUnwrap($type->keyType));
    }

    #[\Override]
    public function isSubtypeT(IsSubtypeT $type): string
    {
        return \sprintf(
            '(%s is %s)',
            $this->stringify($type->leftType),
            $this->stringify($type->rightType),
        );
    }

    #[\Override]
    public function ternaryT(TernaryT $type): string
    {
        return \sprintf(
            '(%s ? %s : %s)',
            $this->stringify($type->conditionType),
            $this->stringify($type->thenType),
            $this->stringify($type->elseType),
        );
    }

    #[\Override]
    public function aliasT(AliasT $type): string
    {
        return $this->constructor(\sprintf('%s@%s', $type->class, $type->name), $type->templateArguments);
    }

    #[\Override]
    public function templateT(TemplateT $type): string
    {
        return $this->templateNames[$type] ??= 'T#' . ($this->unknownTemplateIndex++);
    }

    /**
     * @param list<Template> $templates
     */
    protected function templates(array $templates): string
    {
        if ($templates === []) {
            return '';
        }

        return \sprintf('<%s>', implode(', ', array_map($this->template(...), $templates)));
    }

    protected function template(Template $template): string
    {
        $lowerBound = $this->stringifyUnwrap($template->lowerBound);
        $upperBound = $this->stringifyUnwrap($template->upperBound);

        return \sprintf(
            '%s%s%s%s%s',
            match ($template->variance) {
                Variance::Invariant => '',
                Variance::Covariant => 'out ',
                Variance::Contravariant => 'in ',
            },
            $this->templateNames[$template->type] ??= $template->name,
            $upperBound === 'mixed' ? '' : ' of ' . $upperBound,
            $lowerBound === 'never' ? '' : ' super ' . $lowerBound,
            $template->default === null ? '' : ' = ' . $this->stringifyUnwrap($template->default),
        );
    }

    #[\Override]
    public function mixedT(MixedT $type): string
    {
        return 'mixed';
    }

    /**
     * @return non-empty-string
     */
    private function stringify(Type $type): string
    {
        return $type->accept($this);
    }

    /**
     * @return non-empty-string
     */
    private function stringifyUnwrap(Type $type): string
    {
        $string = $type->accept($this);

        if ($string[0] === '(') {
            /** @phpstan-ignore return.type */
            return substr($string, 1, -1);
        }

        return $string;
    }
}
