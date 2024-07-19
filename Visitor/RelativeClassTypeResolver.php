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

    public function self(Type $type, array $typeArguments, null|NamedClassId|AnonymousClassId $resolvedClassId): mixed
    {
        if ($resolvedClassId !== null) {
            return $type;
        }

        return types::self($this->replaceTypes($typeArguments), $this->self);
    }

    public function parent(Type $type, array $typeArguments, ?NamedClassId $resolvedClassId): mixed
    {
        if ($resolvedClassId !== null) {
            return $type;
        }

        return types::parent($this->replaceTypes($typeArguments), $this->parent);
    }

    public function static(Type $type, array $typeArguments, null|NamedClassId|AnonymousClassId $resolvedClassId): mixed
    {
        return types::static($this->replaceTypes($typeArguments), $this->self);
    }
}
