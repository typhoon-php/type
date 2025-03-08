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
enum NonNegativeIntT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var IntRangeT */
        static $type = new IntRangeT(0, null);

        return $visitor->intRange($type);
    }
}
