<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class DiffT implements Type
{
    public readonly Type $minuend;

    public readonly Type $subtrahend;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(Type $minuend, Type $subtrahend)
    {
        $this->minuend = $minuend;
        $this->subtrahend = $subtrahend;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->diff($this);
    }
}
