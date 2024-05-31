<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\DeclarationId\ClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class SelfParentStaticTypeResolver extends RecursiveTypeReplacer
{
    public function __construct(
        private readonly ClassId $self,
        private readonly ?NamedClassId $parent,
    ) {}

    public function self(Type $self, ?ClassId $resolvedClass, array $arguments): mixed
    {
        if ($resolvedClass !== null) {
            return $self;
        }

        return types::self($this->self, ...$this->processTypes($arguments));
    }

    public function parent(Type $self, ?NamedClassId $resolvedClass, array $arguments): mixed
    {
        if ($resolvedClass !== null) {
            return $self;
        }

        return types::parent($this->parent, ...$this->processTypes($arguments));
    }

    public function static(Type $self, ?ClassId $resolvedClass, array $arguments): mixed
    {
        return types::static($this->self, ...$this->processTypes($arguments));
    }
}
