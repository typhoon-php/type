<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @template-covariant TType
 * @implements Type<TType>
 */
final class NonEmptyType implements Type
{
    /**
     * @param Type<TType> $type
     */
    public function __construct(
        private readonly Type $type,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->nonEmpty($this, $this->type);
    }
}
