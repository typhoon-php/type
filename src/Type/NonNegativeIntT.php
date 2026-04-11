<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @implements Type<non-negative-int>
 * @codeCoverageIgnore
 */
enum NonNegativeIntT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->nonNegativeIntT($this);
    }
}
