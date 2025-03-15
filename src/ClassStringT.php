<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<class-string>
 */
final class ClassStringT implements Type
{
    public readonly Type $objectType;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(Type $objectType)
    {
        $this->objectType = $objectType;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->classString($this);
    }
}
