<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class UnionT implements Type
{
    /**
     * @param non-empty-list<Type<T>> $types
     */
    public function __construct(
        public array $types,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->unionT($this);
    }
}
