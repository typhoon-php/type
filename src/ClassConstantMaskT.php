<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class ClassConstantMaskT implements Type
{
    /** @var Type<object> */
    public readonly Type $objectType;

    public readonly string $namePrefix;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param Type<object> $objectType
     */
    public function __construct(Type $objectType, string $namePrefix)
    {
        $this->objectType = $objectType;
        $this->namePrefix = $namePrefix;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->classConstantMask($this);
    }
}
