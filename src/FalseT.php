<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<false>
 */
enum FalseT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->false($this);
    }
}
