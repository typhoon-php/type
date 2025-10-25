<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T of object = object
 */
final readonly class SuperClass
{
    /**
     * @param class-string<T> $class
     * @param list<Type> $templateArguments
     */
    public function __construct(
        public string $class,
        public array $templateArguments = [],
    ) {}
}
