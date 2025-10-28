<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\ArrayDefaultT;
use Typhoon\Type\ArrayT;
use Typhoon\Type\BitmaskT;
use Typhoon\Type\BoolT;
use Typhoon\Type\CallableDefaultT;
use Typhoon\Type\ClassConstantT;
use Typhoon\Type\ConstantT;
use Typhoon\Type\FalseT;
use Typhoon\Type\FloatT;
use Typhoon\Type\FloatValueT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\IntT;
use Typhoon\Type\IntValueT;
use Typhoon\Type\IterableDefaultT;
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
use function Typhoon\floatToString;
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

    public function neverT(NeverT $type): mixed
    {
        return false;
    }

    public function voidT(VoidT $type): mixed
    {
        return false;
    }

    public function nullT(NullT $type): mixed
    {
        return $this->value === null;
    }

    public function falseT(FalseT $type): mixed
    {
        return $this->value === false;
    }

    public function trueT(TrueT $type): mixed
    {
        return $this->value === true;
    }

    public function boolT(BoolT $type): mixed
    {
        return \is_bool($this->value);
    }

    public function intT(IntT $type): mixed
    {
        return \is_int($this->value);
    }

    public function intValueT(IntValueT $type): mixed
    {
        return $this->value === $type->value;
    }

    public function intRangeT(IntRangeT $type): mixed
    {
        return \is_int($this->value)
            && ($type->min === null || $this->value >= $type->min)
            && ($type->max === null || $this->value <= $type->max);
    }

    public function nonZeroIntT(NonZeroIntT $type): mixed
    {
        return \is_int($this->value) && $this->value !== 0;
    }

    public function floatT(FloatT $type): mixed
    {
        return \is_float($this->value);
    }

    public function floatValueT(FloatValueT $type): mixed
    {
        return \is_float($this->value) && floatToString($this->value) === $type->value;
    }

    public function stringT(StringT $type): mixed
    {
        return \is_string($this->value);
    }

    public function nonEmptyStringT(NonEmptyStringT $type): mixed
    {
        return \is_string($this->value) && $this->value !== '';
    }

    public function truthyStringT(TruthyStringT $type): mixed
    {
        return \is_string($this->value) && $this->value;
    }

    public function numericStringT(NumericStringT $type): mixed
    {
        return \is_string($this->value) && is_numeric($this->value);
    }

    public function lowercaseStringT(LowercaseStringT $type): mixed
    {
        return \is_string($this->value) && strtolower($this->value) === $this->value;
    }

    public function stringValueT(StringValueT $type): mixed
    {
        return $this->value === $type->value;
    }

    public function scalarT(ScalarT $type): mixed
    {
        return \is_scalar($this->value);
    }

    public function numericT(NumericT $type): mixed
    {
        return is_numeric($this->value);
    }

    public function resourceT(ResourceT $type): mixed
    {
        return \is_resource($this->value);
    }

    public function arrayDefaultT(ArrayDefaultT $type): mixed
    {
        return \is_array($this->value);
    }

    public function callableDefaultT(CallableDefaultT $type): mixed
    {
        return \is_callable($this->value);
    }

    public function iterableDefaultT(IterableDefaultT $type): mixed
    {
        return is_iterable($this->value);
    }

    public function objectDefaultT(ObjectDefaultT $type): mixed
    {
        return \is_object($this->value);
    }

    public function bitmaskT(BitmaskT $type): mixed
    {
        return \is_int($this->value) && $this->value & $type->intType->accept(new ResolveBitmask());
    }

    public function constantT(ConstantT $type): mixed
    {
        if (!\defined($type->name)) {
            throw new \LogicException(\sprintf('Constant `%s` is not defined', $type->name));
        }

        return $this->value === \constant($type->name);
    }

    public function classConstantT(ClassConstantT $type): mixed
    {
        $lastConstant = null;

        foreach ($type->classType->accept(new ResolveClasses()) as $class) {
            $constant = $class . '::' . $type->name;

            if (\defined($constant)) {
                $lastConstant = $constant;
            }
        }

        if ($lastConstant !== null) {
            return $this->value === \constant($lastConstant);
        }

        $this->fallback($type);
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        if ($type->templateArguments !== []) {
            $this->fallback($type);
        }

        return $this->value instanceof $type->class;
    }

    public function arrayT(ArrayT $type): mixed
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

    public function intersectionT(IntersectionT $type): mixed
    {
        foreach ($type->types as $each) {
            if (!$each->accept($this)) {
                return false;
            }
        }

        return true;
    }

    public function unionT(UnionT $type): mixed
    {
        foreach ($type->types as $each) {
            if ($each->accept($this)) {
                return true;
            }
        }

        return false;
    }

    public function mixedT(MixedT $type): mixed
    {
        return true;
    }

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
