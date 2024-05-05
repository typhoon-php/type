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
final class StaticTypeResolver extends RecursiveTypeReplacer
{
    public function __construct(
        private readonly ClassId|AnonymousClassId $class,
        private readonly bool $final = false,
    ) {}

    public function static(Type $self, ClassId|AnonymousClassId $class, array $arguments): mixed
    {
        if ($this->final) {
            return types::object($this->class, ...$this->processTypes($arguments));
        }

        return types::static($this->class, ...$this->processTypes($arguments));
    }
}
