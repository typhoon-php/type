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
final readonly class ClassConstantT implements Type
{
    /**
     * @param class-string $class
     * @param non-empty-string $name
     */
    public function __construct(
        public string $class,
        public string $name,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classConstantT($this);
    }
}
