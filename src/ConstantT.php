<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class ConstantT implements Type
{
    /** @var non-empty-string */
    public readonly string $name;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param non-empty-string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->constant($this);
    }
}
