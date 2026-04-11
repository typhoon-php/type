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
final readonly class ConstantMaskT implements Type
{
    public function __construct(
        public Mask $mask,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->constantMaskT($this);
    }
}
