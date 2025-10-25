<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<array<mixed>>
 */
final readonly class ArrayT implements Type
{
    /**
     * @param array<ArrayElement> $elements
     */
    public function __construct(
        public Type $key = ArrayKeyT::T,
        public Type $value = MixedT::T,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->array($this);
    }
}
