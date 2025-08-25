<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\TermType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements TermType<string>
 */
enum StringT implements TermType
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->string($this);
    }
}
