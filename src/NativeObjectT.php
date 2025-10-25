<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<object>
 */
enum NativeObjectT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new ObjectT();

        /** @var Type<object> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
