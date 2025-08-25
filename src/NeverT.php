<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\AtomicType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements AtomicType<never>
 */
enum NeverT implements AtomicType
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->never($this);
    }
}
