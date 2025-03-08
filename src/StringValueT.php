<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<string>
 */
final class StringValueT implements Type
{
    public readonly string $value;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->stringValue($this);
    }
}
