<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template K = mixed
 * @template V = mixed
 * @implements Type<iterable<K, V>>
 * @codeCoverageIgnore
 */
final readonly class IterableT implements Type
{
    /**
     * @param Type<K> $keyType
     * @param Type<V> $valueType
     */
    public function __construct(
        public Type $keyType = MixedT::T,
        public Type $valueType = MixedT::T,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->iterableT($this);
    }
}
