<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Visitor;

use Typhoon\Type\AliasT;
use Typhoon\Type\ArrayElement;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\CallableT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ClassT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IsSubtypeT;
use Typhoon\Type\IsSupertypeT;
use Typhoon\Type\IterableT;
use Typhoon\Type\KeyOfT;
use Typhoon\Type\ListT;
use Typhoon\Type\LiteralT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\OffsetT;
use Typhoon\Type\Parameter;
use Typhoon\Type\ParentDefaultT;
use Typhoon\Type\ParentT;
use Typhoon\Type\Property;
use Typhoon\Type\SelfDefaultT;
use Typhoon\Type\SelfT;
use Typhoon\Type\StaticDefaultT;
use Typhoon\Type\StaticT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\Template;
use Typhoon\Type\TemplateT;
use Typhoon\Type\TernaryT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\ValueOfT;
use Typhoon\Type\Variance;
use Typhoon\Type\Visitor;

/**
 * @api
 * @implements Visitor<non-empty-string>
 */
abstract class Stringify implements Visitor
{
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
    public function bitmaskT(BitmaskT $type): string
    {
        return \sprintf('int-mask-of<%s>', $type->intType->accept($this));
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): string
    {
        return $type->value;
    }

    #[\Override]
    public function floatRangeT(FloatRangeT $type): string
    {
        return \sprintf('float<%s, %s>', $type->min ?? 'min', $type->max ?? 'max');
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
        return \sprintf('class-string<%s>', $type->objectType->accept($this));
    }

    #[\Override]
    public function listT(ListT $type): string
    {
        $value = $type->valueType->accept($this);

        $elements = implode(', ', array_map(fn(Type $type): string => $type->accept($this), $type->elements));

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
    public function arrayT(ArrayT $type): string
    {
        $value = $type->valueType->accept($this);

        $elements = implode(', ', array_map($this->arrayElement(...), $type->elements));

        if ($value === 'never') {
            return \sprintf('array{%s}', $elements);
        }

        $name = $type->isNonEmpty ? 'non-empty-array' : 'array';

        $key = $type->keyType->accept($this);

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
            $element->type->accept($this),
        );
    }

    #[\Override]
    public function iterableT(IterableT $type): string
    {
        $key = $type->keyType->accept($this);
        $value = $type->valueType->accept($this);

        if ($key === 'mixed') {
            if ($value === 'mixed') {
                return 'iterable';
            }

            return \sprintf('iterable<%s>', $value);
        }

        return \sprintf('iterable<%s, %s>', $key, $value);
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
            'object%s%s{%s}',
            $this->templates($type->templates),
            implode('', array_map(
                fn(NamedObjectT $inherited): string => '@' . $this->namedObjectT($inherited),
                $type->superTypes,
            )),
            implode(', ', array_map($this->property(...), $type->properties)),
        );
    }

    /**
     * @return non-empty-string
     */
    protected function property(Property $property): string
    {
        return \sprintf('%s%s: %s', $property->name, $property->isOptional ? '?' : '', $property->type->accept($this));
    }

    #[\Override]
    public function selfDefaultT(SelfDefaultT $type): mixed
    {
        return 'self';
    }

    #[\Override]
    public function selfT(SelfT $type): string
    {
        return $this->constructor('self', $type->templateArguments);
    }

    #[\Override]
    public function parentDefaultT(ParentDefaultT $type): mixed
    {
        return 'parent';
    }

    #[\Override]
    public function parentT(ParentT $type): string
    {
        return $this->constructor('parent', $type->templateArguments);
    }

    #[\Override]
    public function staticDefaultT(StaticDefaultT $type): mixed
    {
        return 'static';
    }

    #[\Override]
    public function staticT(StaticT $type): string
    {
        return $this->constructor('static', $type->templateArguments);
    }

    #[\Override]
    public function callableT(CallableT $type): string
    {
        return \sprintf(
            'callable%s(%s): %s',
            $this->templates($type->templates),
            implode(', ', array_map($this->parameter(...), $type->parameters)),
            str_contains($return = $type->returnType->accept($this), 'callable')
                ? \sprintf('(%s)', $return)
                : $return,
        );
    }

    #[\Override]
    public function closureT(ClosureT $type): string
    {
        return \sprintf(
            'Closure%s(%s): %s',
            $this->templates($type->templates),
            implode(', ', array_map($this->parameter(...), $type->parameters)),
            $type->returnType->accept($this),
        );
    }

    /**
     * @return non-empty-string
     */
    protected function parameter(Parameter $parameter): string
    {
        $string = $parameter->type->accept($this);

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
                $string .= $parameter->defaultType->accept($this);
            }
        }

        return $string;
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

        return \sprintf('%s<%s>', $name, implode(', ', array_map(
            fn(Type $type): string => $type->accept($this),
            $templateArguments,
        )));
    }

    #[\Override]
    public function constantT(ConstantT $type): string
    {
        return \sprintf('const<%s>', $type->name);
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): string
    {
        return \sprintf('%s::%s', $type->classType->accept($this), $type->name);
    }

    #[\Override]
    public function classConstantMaskT(ClassConstantMaskT $type): string
    {
        return \sprintf('%s::%s', $type->classType->accept($this), $type->mask);
    }

    #[\Override]
    public function aliasT(AliasT $type): string
    {
        return \sprintf('%s@%s', $type->class, $type->name);
    }

    #[\Override]
    public function literalT(LiteralT $type): string
    {
        return \sprintf('literal<%s>', $type->type->accept($this));
    }

    #[\Override]
    public function intersectionT(IntersectionT $type): string
    {
        return implode('&', array_map(fn(Type $type): string => $type->accept($this), $type->types));
    }

    #[\Override]
    public function unionT(UnionT $type): string
    {
        return \sprintf('(%s)', implode('|', array_map(fn(Type $type): string => $type->accept($this), $type->types)));
    }

    #[\Override]
    public function keyOfT(KeyOfT $type): string
    {
        return \sprintf('key-of<%s>', $type->arrayType->accept($this));
    }

    #[\Override]
    public function valueOfT(ValueOfT $type): string
    {
        return \sprintf('value-of<%s>', $type->arrayType->accept($this));
    }

    #[\Override]
    public function offsetT(OffsetT $type): string
    {
        return \sprintf('%s[%s]', $type->arrayType->accept($this), $type->keyType->accept($this));
    }

    #[\Override]
    public function isSubtypeT(IsSubtypeT $type): string
    {
        return \sprintf('(%s <: %s)', $type->leftType->accept($this), $type->rightType->accept($this));
    }

    #[\Override]
    public function isSupertypeT(IsSupertypeT $type): mixed
    {
        return \sprintf('(%s :> %s)', $type->leftType->accept($this), $type->rightType->accept($this));
    }

    #[\Override]
    public function ternaryT(TernaryT $type): string
    {
        return \sprintf('(%s ? %s : %s)', $type->conditionType->accept($this), $type->thenType->accept($this), $type->elseType->accept($this));
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
        $lowerBound = $template->lowerBound->accept($this);
        $upperBound = $template->upperBound->accept($this);

        return \sprintf(
            '%s%s%s%s%s',
            match ($template->variance) {
                Variance::Invariant => '',
                Variance::Covariant => 'out ',
                Variance::Contravariant => 'in ',
            },
            $this->templateNames()[$template->type] ??= $template->name,
            $upperBound === 'mixed' ? '' : ' of ' . $upperBound,
            $lowerBound === 'never' ? '' : ' super ' . $lowerBound,
            $template->default === null ? '' : ' = ' . $template->default->accept($this),
        );
    }

    /** @var ?\SplObjectStorage<TemplateT, non-empty-string> */
    private ?\SplObjectStorage $templateNames = null;

    /**
     * @return \SplObjectStorage<TemplateT, non-empty-string>
     */
    final protected function templateNames(): \SplObjectStorage
    {
        if ($this->templateNames !== null) {
            return $this->templateNames;
        }

        /** @var \SplObjectStorage<TemplateT, non-empty-string> */
        $templates = new \SplObjectStorage();

        return $this->templateNames = $templates;
    }

    private int $unknownTemplateIndex = 0;

    #[\Override]
    public function templateT(TemplateT $type): string
    {
        return $this->templateNames()[$type] ??= 'T#' . ($this->unknownTemplateIndex++);
    }
}
