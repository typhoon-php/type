<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<object>
 */
enum StaticDefaultT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        /** @var StaticT */
        static $type = new StaticT();

        return $visitor->staticT($type);
    }
}
