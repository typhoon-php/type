<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<object>
 */
final class ObjectT implements Type
{
    /** @var array<non-empty-string, Property> */
    public readonly array $properties;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param array<non-empty-string, Property> $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->object($this);
    }
}
