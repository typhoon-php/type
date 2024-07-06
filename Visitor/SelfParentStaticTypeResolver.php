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

    public function self(Type $type, ?ClassId $resolvedClass, array $typeArguments): mixed
    {
        if ($resolvedClass !== null) {
            return $type;
        }

        return types::self($this->self, ...$this->processTypes($typeArguments));
    }

    public function parent(Type $type, ?NamedClassId $resolvedClass, array $typeArguments): mixed
    {
        if ($resolvedClass !== null) {
            return $type;
        }

        return types::parent($this->parent, ...$this->processTypes($typeArguments));
    }

    public function static(Type $type, ?ClassId $resolvedClass, array $typeArguments): mixed
    {
        return types::static($this->self, ...$this->processTypes($typeArguments));
    }
}
