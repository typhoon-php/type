<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of bool = bool
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class IsSupertypeT implements Type
{
    public function __construct(
        public Type $left,
        public Type $right,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->isSupertypeT($this);
    }
}
