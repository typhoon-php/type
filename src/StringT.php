<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\AtomicType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements AtomicType<string>
 */
enum StringT implements AtomicType
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->string($this);
    }
}
