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
 * @codeCoverageIgnore
 */
final readonly class TernaryT implements Type
{
    /**
     * @param Type<bool> $conditionType
     * @param Type<Then> $thenType
     * @param Type<Else> $elseType
     */
    public function __construct(
        public Type $conditionType,
        public Type $thenType,
        public Type $elseType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->ternaryT($this);
    }
}
