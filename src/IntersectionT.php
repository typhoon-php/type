<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class IntersectionT implements Type
{
    /**
     * @param non-empty-list<Type> $of
     */
    public function __construct(
        public array $of,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intersection($this);
    }
}
