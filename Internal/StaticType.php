<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @psalm-immutable
 * @implements Type<object>
 */
final class StaticType implements Type
{
    /**
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly null|NamedClassId|AnonymousClassId $resolvedClass,
        private readonly array $arguments,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->static($this, $this->resolvedClass, $this->arguments);
    }
}
