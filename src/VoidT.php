<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<void>
 */
enum VoidT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->void($this);
    }
}
