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
 * @codeCoverageIgnore
 */
final readonly class ClassConstantMaskT implements Type
{
    /**
     * @param non-empty-string $mask
     */
    public function __construct(
        public Type $classType,
        public string $mask,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classConstantMaskT($this);
    }
}
