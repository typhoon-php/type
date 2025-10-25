<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<positive-int>
 */
enum PositiveIntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new IntRangeT('1', null);

        /** @var Type<positive-int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
