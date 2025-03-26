<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\IntRangeT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\UnionT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<negative-int|positive-int>
 */
enum NonZeroIntT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var UnionT */
        static $type = new UnionT([
            new IntRangeT(null, '-1'),
            new IntRangeT('1', null),
        ]);

        return $visitor->union($type);
    }
}
