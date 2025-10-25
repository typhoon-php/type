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
 */
final readonly class ConstantT implements Type
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->constant($this);
    }
}
