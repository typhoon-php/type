<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<scalar>
 */
enum ScalarT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            BoolT::T,
            IntT::T,
            FloatT::T,
            StringT::T,
        ]);

        /** @var Type<scalar> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
