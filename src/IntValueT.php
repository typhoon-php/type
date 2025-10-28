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
 * @codeCoverageIgnore
 */
final readonly class IntValueT implements Type
{
    /**
     * @param T $value
     */
    public function __construct(
        public int $value,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intValueT($this);
    }
}
