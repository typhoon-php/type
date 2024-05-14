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
final class SelfParentStaticTypeResolver extends RecursiveTypeReplacer
{
    public function __construct(
        private readonly ClassId|AnonymousClassId $self,
        private readonly ?ClassId $parent,
    ) {}

    public function self(Type $self, null|ClassId|AnonymousClassId $resolvedClass, array $arguments): mixed
    {
        if ($resolvedClass !== null) {
            return $self;
        }

        return types::self($this->self, ...$this->processTypes($arguments));
    }

    public function parent(Type $self, ?ClassId $resolvedClass, array $arguments): mixed
    {
        if ($resolvedClass !== null) {
            return $self;
        }

        return types::parent($this->parent, ...$this->processTypes($arguments));
    }

    public function static(Type $self, null|ClassId|AnonymousClassId $resolvedClass, array $arguments): mixed
    {
        return types::static($this->self, ...$this->processTypes($arguments));
    }
}
