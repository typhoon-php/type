<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class IntersectionT implements Type
{
    /**
     * @param non-empty-list<Type> $types
     */
    public function __construct(
        public array $types,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intersectionT($this);
    }
}
