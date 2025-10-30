<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T of mixed = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class TernaryT implements Type
{
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
