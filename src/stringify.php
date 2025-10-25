<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Visitor\Stringify;

/**
 * @api
 * @return non-empty-string
 */
function stringify(Type $type): string
{
    /** @var ?Stringify */
    static $stringifier = null;
    $stringifier ??= new class extends Stringify {};

    return $type->accept($stringifier);
}
