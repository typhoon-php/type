<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\Type;
use ExtendedTypeSystem\TypeVisitor;

/**
 * @psalm-api
 * @psalm-immutable
 * @implements Type<bool>
 */
enum BoolType implements Type
{
    case self;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitBool($this);
    }
}
