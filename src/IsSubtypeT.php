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
final readonly class IsSubtypeT implements Type
{
    public function __construct(
        public Type $leftType,
        public Type $rightType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->isSubtypeT($this);
    }
}
