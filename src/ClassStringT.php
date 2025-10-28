<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of object = object
 * @implements Type<class-string<T>>
 * @codeCoverageIgnore
 */
final readonly class ClassStringT implements Type
{
    /**
     * @param Type<T> $object
     */
    public function __construct(
        public Type $object,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classStringT($this);
    }
}
