<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @template-covariant TValue of string
 * @implements Type<TValue>
 */
final class StringValueType implements Type
{
    /**
     * @param TValue $value
     */
    public function __construct(
        private readonly string $value,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->stringValue($this, $this->value);
    }
}
