<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\ArrayDefaultT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableDefaultT;
use Typhoon\Type\ClassConstantMaskT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableDefaultT;
use Typhoon\Type\IterableT;
use Typhoon\Type\ListT;
use Typhoon\Type\LowercaseStringT;
use Typhoon\Type\MixedT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\NeverT;
use Typhoon\Type\NonEmptyStringT;
use Typhoon\Type\NonZeroIntT;
use Typhoon\Type\NullT;
use Typhoon\Type\NumericStringT;
use Typhoon\Type\NumericT;
use Typhoon\Type\ObjectDefaultT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\ScalarT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\TrueT;
use Typhoon\Type\TruthyStringT;
use Typhoon\Type\Type;
use Typhoon\Type\UnionT;
use Typhoon\Type\Visitor\Fallback;
use Typhoon\Type\VoidT;
use function Typhoon\Type\stringify;

/**
 * @internal
 * @extends Fallback<bool>
 */
final class Is extends Fallback
{
    public function __construct(
        private readonly mixed $value,
    ) {}

    #[\Override]
    public function neverT(NeverT $type): bool
    {
        return false;
    }

    #[\Override]
    public function voidT(VoidT $type): bool
    {
        return false;
    }

    #[\Override]
    public function nullT(NullT $type): bool
    {
        return $this->value === null;
    }

    #[\Override]
    public function falseT(FalseT $type): bool
    {
        return $this->value === false;
    }

    #[\Override]
    public function trueT(TrueT $type): bool
    {
        return $this->value === true;
    }

    #[\Override]
    public function boolT(BoolT $type): bool
    {
        return \is_bool($this->value);
    }

    #[\Override]
    public function intT(IntT $type): bool
    {
        return \is_int($this->value);
    }

    #[\Override]
    public function intValueT(IntValueT $type): bool
    {
        return $this->value === $type->value;
    }

    #[\Override]
    public function bitmaskT(BitmaskT $type): bool
    {
        return \is_int($this->value) && $this->value & $type->intType->accept(new ResolveBitmask());
    }

    #[\Override]
    public function intRangeT(IntRangeT $type): bool
    {
        return \is_int($this->value)
            && ($type->min === null || $this->value >= $type->min)
            && ($type->max === null || $this->value <= $type->max);
    }

    #[\Override]
    public function nonZeroIntT(NonZeroIntT $type): bool
    {
        return \is_int($this->value) && $this->value !== 0;
    }

    #[\Override]
    public function floatT(FloatT $type): bool
    {
        return \is_float($this->value);
    }

    #[\Override]
    public function floatValueT(FloatValueT $type): bool
    {
        return \is_float($this->value) && $type->value->isEqualTo($this->value);
    }

    #[\Override]
    public function floatRangeT(FloatRangeT $type): mixed
    {
        return \is_float($this->value)
            && ($type->min?->isLessThanOrEqualTo($this->value) ?? true)
            && ($type->max?->isGreaterThanOrEqualTo($this->value) ?? true);
    }

    #[\Override]
    public function stringT(StringT $type): bool
    {
        return \is_string($this->value);
    }

    #[\Override]
    public function nonEmptyStringT(NonEmptyStringT $type): bool
    {
        return \is_string($this->value) && $this->value !== '';
    }

    #[\Override]
    public function truthyStringT(TruthyStringT $type): bool
    {
        return \is_string($this->value) && $this->value;
    }

    #[\Override]
    public function numericStringT(NumericStringT $type): bool
    {
        return \is_string($this->value) && is_numeric($this->value);
    }

    #[\Override]
    public function lowercaseStringT(LowercaseStringT $type): bool
    {
        return \is_string($this->value) && strtolower($this->value) === $this->value;
    }

    #[\Override]
    public function stringValueT(StringValueT $type): bool
    {
        return $this->value === $type->value;
    }

    #[\Override]
    public function scalarT(ScalarT $type): bool
    {
        return \is_scalar($this->value);
    }

    #[\Override]
    public function numericT(NumericT $type): bool
    {
        return is_numeric($this->value);
    }

    #[\Override]
    public function resourceT(ResourceT $type): bool
    {
        return \is_resource($this->value);
    }

    #[\Override]
    public function listT(ListT $type): mixed
    {
        if (!\is_array($this->value) || !array_is_list($this->value)) {
            return false;
        }

        if ($type->isNonEmpty && $this->value === []) {
            return false;
        }

        foreach ($type->elementTypes as $index => $elementType) {
            if (!\array_key_exists($index, $this->value)) {
                return false;
            }

            if (!is($this->value[$index], $elementType)) {
                return false;
            }
        }

        foreach (\array_slice($this->value, \count($type->elementTypes)) as $value) {
            /** @phpstan-ignore function.alreadyNarrowedType */
            if (!is($value, $type->valueType)) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function arrayDefaultT(ArrayDefaultT $type): bool
    {
        return \is_array($this->value);
    }

    #[\Override]
    public function arrayT(ArrayT $type): bool
    {
        if (!\is_array($this->value)) {
            return false;
        }

        if ($type->isNonEmpty && $this->value === []) {
            return false;
        }

        $remainingElements = $this->value;

        foreach ($type->elements as $element) {
            if (!\array_key_exists($element->key, $this->value)) {
                if ($element->isOptional) {
                    continue;
                }

                return false;
            }

            if (!is($this->value[$element->key], $element->type)) {
                return false;
            }

            unset($remainingElements[$element->key]);
        }

        foreach ($remainingElements as $key => $value) {
            /** @phpstan-ignore function.alreadyNarrowedType, function.alreadyNarrowedType */
            if (!is($key, $type->keyType) || !is($value, $type->valueType)) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function objectDefaultT(ObjectDefaultT $type): bool
    {
        return \is_object($this->value);
    }

    #[\Override]
    public function namedObjectT(NamedObjectT $type): bool
    {
        if ($type->templateArguments !== []) {
            $this->fallback($type);
        }

        return $this->value instanceof $type->class;
    }

    #[\Override]
    public function iterableDefaultT(IterableDefaultT $type): bool
    {
        return is_iterable($this->value);
    }

    #[\Override]
    public function iterableT(IterableT $type): mixed
    {
        if (!is_iterable($this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            /** @phpstan-ignore function.alreadyNarrowedType, function.alreadyNarrowedType */
            if (!is($key, $type->keyType) || !is($value, $type->valueType)) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function callableDefaultT(CallableDefaultT $type): bool
    {
        return \is_callable($this->value);
    }

    #[\Override]
    public function intersectionT(IntersectionT $type): bool
    {
        foreach ($type->types as $each) {
            if (!$each->accept($this)) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function unionT(UnionT $type): bool
    {
        foreach ($type->types as $each) {
            if ($each->accept($this)) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function constantT(ConstantT $type): bool
    {
        if (!\defined($type->name)) {
            throw new \LogicException(\sprintf('Constant `%s` is not defined', $type->name));
        }

        if ($type->name === 'NAN') {
            return \is_float($this->value) && is_nan($this->value);
        }

        return $this->value === \constant($type->name);
    }

    #[\Override]
    public function classConstantT(ClassConstantT $type): bool
    {
        $constant = $type->class . '::' . $type->name;

        if (!\defined($constant)) {
            throw new \LogicException(\sprintf('Constant `%s` is not defined', $constant));
        }

        return $this->value === \constant($constant);
    }

    #[\Override]
    public function classConstantMaskT(ClassConstantMaskT $type): bool
    {
        foreach ((new \ReflectionClass($type->class))->getConstants(\ReflectionClassConstant::IS_PUBLIC) as $name => $value) {
            if ($type->mask->match($name) && $this->value === $value) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function mixedT(MixedT $type): bool
    {
        return true;
    }

    #[\Override]
    public function fallback(Type $type): never
    {
        throw new \RuntimeException(\sprintf('Type `%s` is not supported', stringify($type)));
    }
}

/**
 * @internal
 * @template T
 * @param Type<T> $type
 * @psalm-assert-if-true T $value
 */
function is(mixed $value, Type $type): bool
{
    return $type->accept(new Is($value));
}
