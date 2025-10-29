<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<object>
 */
enum SelfDefaultT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        /** @var SelfT */
        static $type = new SelfT();

        return $visitor->selfT($type);
    }
}
