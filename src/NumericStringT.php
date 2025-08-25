<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\TermType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements TermType<numeric-string>
 */
enum NumericStringT implements TermType
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->numericString($this);
    }
}
