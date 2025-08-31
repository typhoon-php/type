<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<null>
 */
enum NullT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->null($this);
    }
}
