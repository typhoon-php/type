<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<numeric>
 */
enum NumericT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            IntT::T,
            FloatT::T,
            NumericStringT::T,
        ]);

        /** @var Type<numeric> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
