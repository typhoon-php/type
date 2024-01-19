<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 * @template-covariant TValue of int|float|string
 * @implements Type<TValue>
 */
final class LiteralType implements Type
{
    /**
     * @var TValue
     */
    public readonly int|float|string $value;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param TValue $value
     */
    public function __construct(
        int|float|string $value,
    ) {
        $this->value = $value;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitLiteral($this);
    }
}
