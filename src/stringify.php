<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\TypeStringifier;

/**
 * @api
 * @return non-empty-string
 */
function stringify(Type $type): string
{
    /** @var ?TypeStringifier */
    static $stringifier = null;
    $stringifier ??= new TypeStringifier();

    return match ($type) {
        Alias\ArrayKeyT::T => 'array-key',
        Alias\ArrayT::T => 'array',
        Alias\BoolT::T => 'bool',
        Alias\FloatT::T => 'float',
        Alias\IntT::T => 'int',
        Alias\MixedT::T => 'mixed',
        Alias\NegativeIntT::T => 'negative-int',
        Alias\NonNegativeIntT::T => 'non-negative-int',
        Alias\NonPositiveIntT::T => 'non-positive-int',
        Alias\NonZeroIntT::T => 'non-zero-int',
        Alias\NumericT::T => 'numeric',
        Alias\PositiveIntT::T => 'positive',
        Alias\ScalarT::T => 'scalar',
        default => $type->accept($stringifier)
    };
}
