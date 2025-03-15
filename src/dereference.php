<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Visitor\RecursiveTypeReplacer;

/**
 * @api
 */
function dereference(Type $type): Type
{
    /** @var ?RecursiveTypeReplacer */
    static $dereferencer = null;
    $dereferencer ??= new class extends RecursiveTypeReplacer {};

    return $type->accept($dereferencer);
}
