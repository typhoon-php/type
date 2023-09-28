<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 * @template-covariant TType
 * @implements Type<TType>
 */
final class FunctionTemplateType implements Type
{
    /**
     * @var callable-string
     */
    public readonly string $function;

    /**
     * @var non-empty-string
     */
    public readonly string $name;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param callable-string $function
     * @param non-empty-string $name
     */
    public function __construct(
        string $function,
        string $name,
    ) {
        $this->name = $name;
        $this->function = $function;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitFunctionTemplate($this);
    }
}