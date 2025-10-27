<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of string = string
 * @implements Type<T>
 */
final readonly class StringValueT implements Type
{
    /**
     * @param T $value
     */
    public function __construct(
        public string $value,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->stringValueT($this);
    }
}
