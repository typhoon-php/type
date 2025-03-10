<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\IntRangeT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<int>
 */
enum NegativeIntT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var IntRangeT */
        static $type = new IntRangeT(null, '-1');

        return $visitor->intRange($type);
    }
}
