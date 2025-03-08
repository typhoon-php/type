<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @template TObject of object = object
 * @implements Type<class-string<TObject>>
 */
final class ClassStringT implements Type
{
    /** @var Type<TObject> */
    public readonly Type $objectType;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param Type<TObject> $objectType
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
