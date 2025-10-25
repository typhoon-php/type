<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<negative-int>
 */
enum NegativeIntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new IntRangeT(null, '-1');

        /** @var Type<negative-int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
