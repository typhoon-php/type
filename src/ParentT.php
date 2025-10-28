<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of object = object
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ParentT implements Type
{
    /**
     * @param list<Type> $templateArguments
     */
    public function __construct(
        public array $templateArguments = [],
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->parentT($this);
    }
}
