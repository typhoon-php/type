<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Visitor\Is;

/**
 * @api
 * @template T
 * @param Type<T> $type
 * @psalm-assert-if-true T $value
 */
function is(mixed $value, Type $type): bool
{
    return $type->accept(new class ($value) extends Is {});
}
