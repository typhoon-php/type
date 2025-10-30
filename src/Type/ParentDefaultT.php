<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @implements Type<object>
 */
enum ParentDefaultT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        /** @var ParentT */
        static $type = new ParentT();

        return $visitor->parentT($type);
    }
}
