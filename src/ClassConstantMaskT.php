<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class ClassConstantMaskT implements Type
{
    public function __construct(
        public Type $on,
        public string $namePrefix,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classConstantMask($this);
    }
}
