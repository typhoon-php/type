<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of float = float
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class FloatValueT implements Type
{
    public function __construct(
        public float $value,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->floatValueT($this);
    }
}
