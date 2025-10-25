<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<void>
 */
enum VoidT implements Type
{
    case T;

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->void($this);
    }
}
