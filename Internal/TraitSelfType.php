<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\DeclarationId\ClassId;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\Visitor\TraitTypesResolver;
use function Typhoon\DeclarationId\aliasId;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @readonly
 * @implements Type<object>
 */
final class TraitSelfType implements Type
{
    /**
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly ClassId $trait,
        private readonly array $arguments,
    ) {}

    /**
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function accept(TypeVisitor $visitor): mixed
    {
        if ($visitor instanceof TraitTypesResolver) {
            /** @phpstan-ignore return.type */
            return $visitor->traitSelf($this->arguments);
        }

        return $visitor->alias($this, aliasId($this->trait, 'self'), $this->arguments);
    }
}
