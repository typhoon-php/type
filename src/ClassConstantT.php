<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class ClassConstantT implements Type
{
    /** @var Type<object> */
    public readonly Type $objectType;

    /** @var non-empty-string */
    public readonly string $name;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param Type<object> $objectType
     * @param non-empty-string $name
     */
    public function __construct(Type $objectType, string $name)
    {
        $this->objectType = $objectType;
        $this->name = $name;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->classConstant($this);
    }
}
