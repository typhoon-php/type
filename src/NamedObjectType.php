<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<object>
 */
final class NamedObjectType implements Type
{
    /**
     * @param non-empty-string $class
     * @param list<Type|VarianceAwareType> $templateArguments
     */
    public function __construct(
        private readonly string $class,
        private readonly array $templateArguments,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->namedObject($this, $this->class, $this->templateArguments);
    }
}
