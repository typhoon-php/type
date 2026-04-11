<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of int = int
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class IntValueT implements Type
{
    /**
     * @param T $value
     */
    public function __construct(
        public int $value,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intValueT($this);
    }
}
