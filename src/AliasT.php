<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class AliasT implements Type
{
    public readonly Type $classType;

    /** @var non-empty-string */
    public readonly string $name;

    /** @var list<Type> */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param non-empty-string $name
     * @param list<Type> $templateArguments
     */
    public function __construct(Type $classType, string $name, array $templateArguments)
    {
        $this->classType = $classType;
        $this->name = $name;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->alias($this);
    }
}
