<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class RelativeClassTypeResolver extends RecursiveTypeReplacer
{
    public function __construct(
        private readonly NamedClassId|AnonymousClassId $self,
        private readonly ?NamedClassId $parent,
    ) {}

    public function self(Type $type, null|NamedClassId|AnonymousClassId $resolvedClass, array $typeArguments): mixed
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

    public function static(Type $type, null|NamedClassId|AnonymousClassId $resolvedClass, array $typeArguments): mixed
    {
        return types::static($this->self, ...$this->processTypes($typeArguments));
    }
}
