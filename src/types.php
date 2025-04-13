<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Alias\ArrayKeyT;
use Typhoon\Type\Alias\BoolT;
use Typhoon\Type\Alias\FloatT;
use Typhoon\Type\Alias\IntT;
use Typhoon\Type\Alias\MixedT;
use Typhoon\Type\Alias\NegativeIntT;
use Typhoon\Type\Alias\NonEmptyStringT;
use Typhoon\Type\Alias\NonNegativeIntT;
use Typhoon\Type\Alias\NonPositiveIntT;
use Typhoon\Type\Alias\NonZeroIntT;
use Typhoon\Type\Alias\NumericT;
use Typhoon\Type\Alias\PositiveIntT;
use Typhoon\Type\Alias\ScalarT;
use function Typhoon\Type\Internal\floatToString;

if (\defined('Typhoon\TypeGenerator\GENERATING')) {
    return;
}

const neverT = NeverT::T;

const voidT = VoidT::T;

const nullT = NullT::T;

const falseT = FalseT::T;

const trueT = TrueT::T;

const boolT = BoolT::T;

const intT = IntT::T;

/**
 * @api
 * @param int|numeric-string $value
 * @return Type<int>
 */
function intT(int|string $value): Type
{
    $value = (string) $value;

    return new IntRangeT($value, $value);
}

/**
 * @api
 * @param null|int|numeric-string $min
 * @param null|int|numeric-string $max
 * @return Type<int>
 */
function intRangeT(null|int|string $min = null, null|int|string $max = null): Type
{
    if ($min === null && $max === null) {
        return IntT::T;
    }

    return new IntRangeT(
        $min === null ? null : (string) $min,
        $max === null ? null : (string) $max,
    );
}

const negativeIntT = NegativeIntT::T;

const nonPositiveIntT = NonPositiveIntT::T;

const nonZeroIntT = NonZeroIntT::T;

const nonNegativeIntT = NonNegativeIntT::T;

const positiveIntT = PositiveIntT::T;

const floatT = FloatT::T;

/**
 * @api
 * @param float|numeric-string $value
 * @return Type<float>
 */
function floatT(float|string $value): Type
{
    if (\is_float($value)) {
        $value = floatToString($value);
    }

    return new FloatRangeT($value, $value);
}

/**
 * @api
 * @param null|float|numeric-string $min
 * @param null|float|numeric-string $max
 * @return Type<float>
 */
function floatRangeT(null|float|string $min = null, null|float|string $max = null): Type
{
    if ($min === null && $max === null) {
        return FloatT::T;
    }

    if (\is_float($min)) {
        $min = floatToString($min);
    }

    if (\is_float($max)) {
        $max = floatToString($max);
    }

    return new FloatRangeT($min, $max);
}

/**
 * @api
 * @return Type<string>
 */
function stringT(string $value): Type
{
    return new StringValueT($value);
}

const nonEmptyStringT = NonEmptyStringT::T;

const numericStringT = NumericStringT::T;

const lowercaseStringT = LowercaseStringT::T;

const stringT = StringT::T;

const resourceT = ResourceT::T;

/**
 * @api
 * @param class-string $class
 * @param list<Type> $templateArguments
 */
function objectT(string $class, array $templateArguments = []): Type
{
    return new NamedObjectT($class, $templateArguments);
}

const numericT = NumericT::T;

const arrayKeyT = ArrayKeyT::T;

const scalarT = ScalarT::T;

const mixedT = MixedT::T;

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function unionT(Type|array $types, Type ...$moreTypes): Type
{
    $types = [...(\is_array($types) ? $types : [$types]), ...$moreTypes];

    return match (\count($types)) {
        0 => NeverT::T,
        1 => $types[0],
        default => new UnionT($types),
    };
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function orT(Type|array $types, Type ...$moreTypes): Type
{
    return unionT($types, ...$moreTypes);
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function intersectionT(Type|array $types, Type ...$moreTypes): Type
{
    $types = [...(\is_array($types) ? $types : [$types]), ...$moreTypes];

    return match (\count($types)) {
        0 => MixedT::T,
        1 => $types[0],
        default => new IntersectionT($types),
    };
}

/**
 * @api
 * @no-named-arguments
 * @param Type|list<Type> $types
 */
function andT(Type|array $types, Type ...$moreTypes): Type
{
    return intersectionT($types, ...$moreTypes);
}

/**
 * @api
 * @template T
 * @param Type<T> $type
 * @return Type<null|T>
 */
function nullOrT(Type $type): Type
{
    return new UnionT([NullT::T, $type]);
}

/**
 * @api
 */
function diffT(Type $minuend, Type $subtrahend): Type
{
    return new DiffT($minuend, $subtrahend);
}

/**
 * @api
 */
function notT(Type $type): Type
{
    return new DiffT(MixedT::T, $type);
}

/**
 * @api
 * @param non-empty-string $name
 */
function templateT(string $name, Variance $variance, Type $upperBound): TemplateT
{
    return new TemplateT($name, $variance, $upperBound);
}
