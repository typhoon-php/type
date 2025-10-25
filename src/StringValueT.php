<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<string>
 */
final readonly class StringValueT implements Type
{
    public function __construct(
        public string $value,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->stringValue($this);
    }
}
