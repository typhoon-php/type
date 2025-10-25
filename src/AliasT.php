<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class AliasT implements Type
{
    /**
     * @param class-string $class
     * @param non-empty-string $name
     * @param list<Type> $templateArguments
     */
    public function __construct(
        public string $class,
        public string $name,
        public array $templateArguments = [],
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->alias($this);
    }
}
