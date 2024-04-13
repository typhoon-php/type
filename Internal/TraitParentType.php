<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\Visitor\TraitTypesResolver;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @readonly
 * @implements Type<object>
 */
final class TraitParentType implements Type
{
    /**
     * @param non-empty-string $trait
     * @param list<Type> $arguments
     */
    public function __construct(
        private readonly string $trait,
        private readonly array $arguments,
    ) {}

    /**
     * @template TReturn
     * @param TypeVisitor<TReturn> $visitor
     * @return TReturn
     */
    public function accept(TypeVisitor $visitor): mixed
    {
        if ($visitor instanceof TraitTypesResolver) {
            /** @var TReturn */
            return $visitor->traitParent($this->arguments);
        }

        return $visitor->alias($this, 'parent', 'trait-' . $this->trait, $this->arguments);
    }
}
