<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<bool>
 */
enum BoolT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->bool($this);
    }
}
