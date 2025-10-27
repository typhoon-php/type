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
final readonly class LiteralT implements Type
{
    /**
     * @param Type<T> $type
     */
    public function __construct(
        public Type $type,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->literalT($this);
    }
}
