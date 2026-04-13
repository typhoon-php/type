<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;
use Typhoon\Type\Internal\EvaluateBitmask;

/**
 * @api
 * @template T of int = int
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class BitmaskT implements Type
{
    public function __construct(
        public Type $intType,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->bitmaskT($this);
    }

    public function evaluate(): int
    {
        return $this->intType->accept(new EvaluateBitmask($this));
    }
}
