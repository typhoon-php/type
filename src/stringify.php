<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Visitor\TypeStringifier;

/**
 * @api
 * @return non-empty-string
 */
function stringify(Type $type): string
{
    /** @var ?TypeStringifier */
    static $stringifier = null;
    $stringifier ??= new TypeStringifier();

    return $type->accept($stringifier);
}
