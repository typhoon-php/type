<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of list = list
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ListT implements Type
{
    /**
     * @param list<Type> $elements
     */
    public function __construct(
        public Type $valueType = MixedT::T,
        public array $elements = [],
        public bool $isNonEmpty = false,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->listT($this);
    }
}
