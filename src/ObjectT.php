<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<object>
 */
final readonly class ObjectT implements Type
{
    /**
     * @param list<Template> $templates
     * @param list<SuperClass> $superClasses
     * @param list<Property> $properties
     */
    public function __construct(
        public array $templates = [],
        public array $superClasses = [],
        public array $properties = [],
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->object($this);
    }
}
