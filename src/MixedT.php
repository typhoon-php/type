<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<mixed>
 */
enum MixedT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            NullT::T,
            TrueT::T,
            FalseT::T,
            IntT::T,
            FloatT::T,
            StringT::T,
            NativeArrayT::T,
            NativeObjectT::T,
            ResourceT::T,
        ]);

        /** @var Type<mixed> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
