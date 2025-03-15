<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\DiffT;
use Typhoon\Type\StringT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<int>
 */
enum NonEmptyStringT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var DiffT */
        static $type = new DiffT(StringT::T, new StringValueT(''));

        return $visitor->diff($type);
    }
}
