<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<positive-int>
 */
final readonly class IntMaskOfT implements Type
{
    public function __construct(
        public Type $of,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intMaskOf($this);
    }
}
