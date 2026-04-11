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
final readonly class UnionT implements Type
{
    /**
     * @param non-empty-list<Type<T>> $types
     */
    public function __construct(
        public array $types,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->unionT($this);
    }
}
