<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class ConstantT implements Type
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->constant($this);
    }
}
