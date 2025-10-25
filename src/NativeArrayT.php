<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<array<mixed>>
 */
enum NativeArrayT implements Shortcut
{
    case T;

    public function dereference(): Type
    {
        static $type = new ArrayT();

        /** @var Type<array<mixed>> */
        return $type;
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
