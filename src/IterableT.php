<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Shortcut<iterable<mixed>>
 */
final class IterableT implements Shortcut
{
    /** @var ?Type<iterable<mixed>> */
    private ?Type $type = null;

    public function __construct(
        public readonly Type $key = MixedT::T,
        public readonly Type $value = MixedT::T,
    ) {}

    public function dereference(): Type
    {
        return $this->type ??= new UnionT([
            new ArrayT(
                key: new IntersectionT([$this->key, ArrayKeyT::T]),
                value: $this->value,
            ),
            new ObjectT(superClasses: [new SuperClass(\Traversable::class, [$this->key, $this->value])]),
        ]);
    }

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->shortcut($this);
    }
}
