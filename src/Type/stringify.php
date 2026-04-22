<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;
use Typhoon\Type\Visitor\Stringify;

if (\function_exists('Typhoon\Type\stringify')) {
    return;
}

/**
 * @api
 * @return non-empty-string
 */
function stringify(Type $type): string
{
    /** @var Stringify */
    static $stringify = new Stringify();

    return $stringify->unsafe($type);
}
