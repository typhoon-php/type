<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant V = mixed
 * @implements Type<list<V>>
 * @codeCoverageIgnore
 */
final readonly class ListT implements Type
{
    /**
     * @param Type<V> $value
     * @param list<ArrayElement> $elements
     */
    public function __construct(
        public Type $value = MixedT::T,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->listT($this);
    }
}
