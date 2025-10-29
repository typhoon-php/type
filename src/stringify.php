<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Visitor\Stringify;

/**
 * @api
 * @return non-empty-string
 */
function stringify(Type $type, bool $unwrap = true): string
{
    $string = $type->accept(new class extends Stringify {});

    if ($unwrap && $string[0] === '(') {
        /** @phpstan-ignore return.type */
        return substr($string, 1, -1);
    }

    return $string;
}
