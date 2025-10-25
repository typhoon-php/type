<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<non-positive-int>
 */
enum NonPositiveIntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new IntRangeT(null, '0');

        /** @var Type<non-positive-int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
