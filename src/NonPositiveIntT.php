<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<non-positive-int>
 * @codeCoverageIgnore
 */
enum NonPositiveIntT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->nonPositiveIntT($this);
    }
}
