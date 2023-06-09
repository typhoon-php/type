<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\Type;
use ExtendedTypeSystem\TypeVisitor;

/**
 * @api
 * @psalm-immutable
 * @implements Type<bool>
 */
enum BoolType implements Type
{
    /**
     * @internal
     * @psalm-internal ExtendedTypeSystem
     */
    case type;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitBool($this);
    }
}
