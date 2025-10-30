<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T of list = list
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ListT implements Type
{
    /**
     * @param list<Type> $elementTypes
     */
    public function __construct(
        public Type $valueType = MixedT::T,
        public array $elementTypes = [],
        public bool $isNonEmpty = false,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->listT($this);
    }
}
