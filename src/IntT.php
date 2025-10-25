<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<int>
 */
enum IntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new IntRangeT();

        /** @var Type<int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
