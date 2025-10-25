<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<float>
 */
enum FloatT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new FloatRangeT();

        /** @var Type<float> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
