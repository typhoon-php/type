<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Alias\ArrayKeyT;
use Typhoon\Type\Alias\BoolT;
use Typhoon\Type\Alias\FloatT;
use Typhoon\Type\Alias\IntT;
use Typhoon\Type\Alias\MixedT;
use Typhoon\Type\Alias\NegativeIntT;
use Typhoon\Type\Alias\NonNegativeIntT;
use Typhoon\Type\Alias\NonPositiveIntT;
use Typhoon\Type\Alias\PositiveIntT;

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
    return new IntRangeT(
        $min === null ? null : (string) $min,
        $max === null ? null : (string) $max,
    );
}

const negativeIntT = NegativeIntT::T;

const nonPositiveIntT = NonPositiveIntT::T;

const nonNegativeIntT = NonNegativeIntT::T;

const positiveIntT = PositiveIntT::T;

const floatT = FloatT::T;

/**
 * @api
 * @return Type<float>
 */
function floatT(float $value): Type
{
    return new FloatRangeT($value, $value);
}

/**
 * @api
 * @return Type<float>
 */
function floatRangeT(?float $min = null, ?float $max = null): Type
{
    return new FloatRangeT($min, $max);
}

const stringT = StringT::T;

/**
 * @api
 * @return Type<string>
 */
function stringT(string $value): Type
{
    return new StringValueT($value);
}

const resourceT = ResourceT::T;

const arrayKeyT = ArrayKeyT::T;

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
