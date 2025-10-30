<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<T>
 */
final readonly class IntersectionT implements Type
{
    /**
     * @param non-empty-list<Type> $types
     */
    public function __construct(
        public array $types,
    ) {
        if (\count($types) < 2) {
            throw new \ValueError(\sprintf('`%s` requires at least two types, got %d', self::class, \count($types)));
        }
    }

    /**
     * @codeCoverageIgnore
     */
    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intersectionT($this);
    }
}
