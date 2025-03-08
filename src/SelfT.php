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
final class SelfT implements Type
{
    /** @var ?Type<TObject> */
    public readonly ?Type $resolvedObjectType;

    /** @var list<Type> */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param ?Type<TObject> $resolvedObjectType
     * @param list<Type> $templateArguments
     */
    public function __construct(?Type $resolvedObjectType, array $templateArguments)
    {
        $this->resolvedObjectType = $resolvedObjectType;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->self($this);
    }
}
