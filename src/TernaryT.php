<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class TernaryT implements Type
{
    public function __construct(
        public Type $condition,
        public Type $then,
        public Type $else,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->ternary($this);
    }
}
