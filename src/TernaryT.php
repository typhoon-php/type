<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant Then = mixed
 * @template-covariant Else = mixed
 * @implements Type<Then|Else>
 */
final readonly class TernaryT implements Type
{
    /**
     * @param Type<bool> $condition
     * @param Type<Then> $then
     * @param Type<Else> $else
     */
    public function __construct(
        public Type $condition,
        public Type $then,
        public Type $else,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->ternary($this);
    }
}
