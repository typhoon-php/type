<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @implements Type<non-positive-int>
 * @codeCoverageIgnore
 */
enum NonPositiveIntT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->nonPositiveIntT($this);
    }
}
