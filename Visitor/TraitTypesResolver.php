<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\ClassId;
use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class TraitTypesResolver extends RecursiveTypeReplacer
{
    public function __construct(
        private readonly ClassId|AnonymousClassId $class,
        private readonly ?ClassId $parent = null,
    ) {}

    /**
     * @param list<Type> $arguments
     */
    public function traitSelf(array $arguments): Type
    {
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
        return types::static($this->class, ...$this->processTypes($arguments));
    }
}
