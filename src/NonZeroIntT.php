<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<non-zero-int>
 */
enum NonZeroIntT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            NegativeIntT::T,
            PositiveIntT::T,
        ]);

        /** @var Type<non-zero-int> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
