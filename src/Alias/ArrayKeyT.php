<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\StringT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\UnionT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<array-key>
 */
enum ArrayKeyT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var UnionT */
        static $type = new UnionT([
            IntT::T,
            StringT::T,
        ]);

        return $visitor->union($type);
    }
}
