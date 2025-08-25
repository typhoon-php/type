<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\AtomicType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements AtomicType<non-empty-string>
 */
enum NonEmptyStringT implements AtomicType
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->nonEmptyString($this);
    }
}
