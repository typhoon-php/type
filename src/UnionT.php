<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class UnionT implements Type
{
    /** @var non-empty-list<Type> */
    public readonly array $types;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param non-empty-list<Type> $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->union($this);
    }
}
