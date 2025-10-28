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
final readonly class ObjectT implements Type
{
    /**
     * @param list<Template> $templates
     * @param list<NamedObjectT> $superTypes
     * @param list<Property> $properties
     */
    public function __construct(
        public array $templates = [],
        public array $superTypes = [],
        public array $properties = [],
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->objectT($this);
    }
}
