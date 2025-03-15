<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @return numeric-string
 */
function floatToString(float $float): string
{
    $string = (string) $float;

    if (!preg_match('~\.(\d+)E([+-])(\d+)$~', $string, $matches)) {
        return $string;
    }

    if ($matches[2] === '+') {
        /** @var numeric-string */
        return number_format($float, thousands_separator: '');
    }

    $decimals = (int) $matches[3];

    if ($matches[1] !== '0') {
        $decimals += \strlen($matches[1]);
    }

    /** @var numeric-string */
    return number_format($float, $decimals, thousands_separator: '');
}
