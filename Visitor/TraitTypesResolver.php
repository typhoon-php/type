<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\RecursiveTypeReplacer;
use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class TraitTypesResolver extends RecursiveTypeReplacer
{
    /**
     * @param non-empty-string $class
     * @param ?non-empty-string $parent
     */
    private function __construct(
        private readonly string $class,
        private readonly ?string $parent = null,
    ) {}

    /**
     * @param list<Type> $arguments
     */
    public function traitSelf(array $arguments): Type
    {
        return types::object($this->class, ...$arguments);
    }

    /**
     * @param list<Type> $arguments
     */
    public function traitParent(array $arguments): Type
    {
        return types::object($this->parent ?? throw new \LogicException(), ...$arguments);
    }

    /**
     * @param list<Type> $arguments
     */
    public function traitStatic(array $arguments): Type
    {
        return types::static($this->class, ...$arguments);
    }
}
