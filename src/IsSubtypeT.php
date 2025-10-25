<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<bool>
 */
final readonly class IsSubtypeT implements Type
{
    public function __construct(
        public Type $left,
        public Type $right,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->isSubtype($this);
    }
}
