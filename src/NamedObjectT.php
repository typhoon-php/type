<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<object>
 */
final class NamedObjectT implements Type
{
    /** @var class-string */
    public readonly string $class;

    /** @var list<Type> */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param class-string $class
     * @param list<Type> $templateArguments
     */
    public function __construct(string $class, array $templateArguments)
    {
        $this->class = $class;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->namedObject($this);
    }
}
