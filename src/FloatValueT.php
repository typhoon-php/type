<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of float = float
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class FloatValueT implements Type
{
    /**
     * @param numeric-string $value
     */
    public function __construct(
        public string $value,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatValueT($this);
    }
}
