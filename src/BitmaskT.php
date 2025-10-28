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
final readonly class BitmaskT implements Type
{
    public function __construct(
        public Type $intType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->bitmaskT($this);
    }
}
