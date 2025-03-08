<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @template TObject of object = object
 * @implements Type<TObject>
 */
final class NamedObjectT implements Type
{
    /** @var class-string<TObject> */
    public readonly string $name;

    /** @var list<Type> */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param class-string<TObject> $name
     * @param list<Type> $templateArguments
     */
    public function __construct(string $name, array $templateArguments)
    {
        $this->name = $name;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->namedObject($this);
    }
}
