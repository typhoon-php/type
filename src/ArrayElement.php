<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class ArrayElement
{
    public function __construct(
        public Type $type,
        public bool $isOptional = false,
    ) {}
}
