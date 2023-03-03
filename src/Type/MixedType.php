<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\Type;
use ExtendedTypeSystem\TypeVisitor;

/**
 * @psalm-api
 * @psalm-immutable
 * @implements Type<mixed>
 */
enum MixedType implements Type
{
    case self;

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitMixed($this);
    }
}
