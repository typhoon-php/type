<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<key-of<T>>
 * @codeCoverageIgnore
 */
final readonly class KeyOfT implements Type
{
    /**
     * @param Type<T> $array
     */
    public function __construct(
        public Type $array,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->keyOfT($this);
    }
}
