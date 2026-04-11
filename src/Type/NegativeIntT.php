<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @implements Type<negative-int>
 * @codeCoverageIgnore
 */
enum NegativeIntT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->negativeIntT($this);
    }
}
