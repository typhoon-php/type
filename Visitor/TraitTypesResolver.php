<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class TraitTypesResolver extends RecursiveTypeReplacer
{
    /**
     * @param ?non-empty-string $class
     * @param ?non-empty-string $parent
     */
    public function __construct(
        private readonly ?string $class,
        private readonly ?string $parent = null,
    ) {}

    /**
     * @param list<Type> $arguments
     */
    public function traitSelf(array $arguments): Type
    {
        if ($this->class === null) {
            return types::anonymousClassSelf(...$this->processTypes($arguments));
        }

        return types::object($this->class, ...$this->processTypes($arguments));
    }

    /**
     * @param list<Type> $arguments
     */
    public function traitParent(array $arguments): Type
    {
        return types::object($this->parent ?? throw new \LogicException(), ...$this->processTypes($arguments));
    }

    /**
     * @param list<Type> $arguments
     */
    public function traitStatic(array $arguments): Type
    {
        if ($this->class === null) {
            return types::anonymousClassSelf(...$this->processTypes($arguments));
        }

        return types::static($this->class, ...$this->processTypes($arguments));
    }
}
