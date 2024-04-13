<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class StaticTypeResolver extends RecursiveTypeReplacer
{
    /**
     * @param non-empty-string $class
     */
    public function __construct(
        private readonly string $class,
    ) {}

    public function static(Type $self, string $class, array $arguments): mixed
    {
        return types::object($this->class, ...array_map(
            fn(Type $templateArgument): Type => $templateArgument->accept($this),
            $arguments,
        ));
    }
}
