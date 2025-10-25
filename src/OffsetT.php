<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class OffsetT implements Type
{
    public function __construct(
        public Type $value,
        public Type $key,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->offset($this);
    }
}
