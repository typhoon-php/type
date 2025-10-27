<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<lowercase-string>
 * @codeCoverageIgnore
 */
enum LowercaseStringT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->lowercaseStringT($this);
    }
}
