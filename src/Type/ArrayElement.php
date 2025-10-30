<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 */
final readonly class ArrayElement
{
    public function __construct(
        public int|string $key,
        public Type $type,
        public bool $isOptional = false,
    ) {}
}
