<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<object>
 */
final class StaticT implements Type
{
    public readonly ?Type $resolvedObjectType;

    /** @var list<Type> */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param list<Type> $templateArguments
     */
    public function __construct(?Type $resolvedObjectType, array $templateArguments)
    {
        $this->resolvedObjectType = $resolvedObjectType;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->static($this);
    }
}
