<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of int = int
 * @implements Type<T>
 */
final readonly class IntValueT implements Type
{
    /**
     * @param T $value
     */
    public function __construct(
        public int $value,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intValue($this);
    }
}
