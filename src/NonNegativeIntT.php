<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<non-negative-int>
 */
enum NonNegativeIntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new IntRangeT('0', null);

        /** @var Type<non-negative-int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
