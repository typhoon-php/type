<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<bool>
 */
enum BoolT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            FalseT::T,
            TrueT::T,
        ]);

        /** @var Type<bool> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
