<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\At;
use Typhoon\Type\AtClass;
use Typhoon\Type\AtFunction;
use Typhoon\Type\AtMethod;
use Typhoon\Type\Type;

/**
 * @api
 * @readonly
 */
final class TemplateTypeResolver extends RecursiveTypeReplacer
{
    /**
     * @var array<non-empty-string, Type>
     */
    private array $typesByHash = [];

    /**
     * @psalm-pure
     * @param non-empty-string $name
     * @return non-empty-string
     */
    private static function hash(string $name, At|AtFunction|AtClass|AtMethod $declaredAt): string
    {
        return $name . '.' . match (true) {
            $declaredAt instanceof At => $declaredAt->name,
            $declaredAt instanceof AtClass => $declaredAt->name,
            $declaredAt instanceof AtFunction => $declaredAt->name,
            $declaredAt instanceof AtMethod => $declaredAt->class . '.' . $declaredAt->name,
        };
    }

    /**
     * @param non-empty-string $name
     */
    public function with(string $name, AtClass|AtFunction|AtMethod $declaredAt, Type $type): self
    {
        $resolver = clone $this;
        $resolver->typesByHash[self::hash($name, $declaredAt)] = $type;

        return $resolver;
    }

    public function template(Type $self, string $name, At|AtFunction|AtClass|AtMethod $declaredAt, array $arguments): mixed
    {
        return $this->typesByHash[self::hash($name, $declaredAt)] ?? $self;
    }
}
