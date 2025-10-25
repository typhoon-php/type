<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<iterable<mixed>>
 */
enum NativeIterableT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new UnionT([
            NativeArrayT::T,
            new ObjectT(superClasses: [new SuperClass(\Traversable::class)]),
        ]);

        /** @var Type<iterable<mixed>> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
