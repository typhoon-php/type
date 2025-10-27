<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<string>
 */
enum StringT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->stringT($this);
    }
}
