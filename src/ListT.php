<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<list>
 */
final readonly class ListT implements Type
{
    /**
     * @param array<non-negative-int, ArrayElement> $elements
     */
    public function __construct(
        public Type $value,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->list($this);
    }
}
