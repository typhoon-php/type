<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of object = object
 * @implements Type<class-string<T>>
 * @codeCoverageIgnore
 */
final readonly class ClassT implements Type
{
    /**
     * @param Type<T> $objectType
     */
    public function __construct(
        public Type $objectType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classT($this);
    }
}
