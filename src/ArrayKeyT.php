<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<array-key>
 */
enum ArrayKeyT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            IntT::T,
            StringT::T,
        ]);

        /** @var Type<array-key> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
