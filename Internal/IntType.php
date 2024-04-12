<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @readonly
 * @implements Type<int>
 */
final class IntType implements Type
{
    public function __construct(
        private readonly ?int $min,
        private readonly ?int $max,
    ) {}

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->int($this, $this->min, $this->max);
    }
}
