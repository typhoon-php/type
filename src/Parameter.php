<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class Parameter
{
    public function __construct(
        public Type $type,
        public bool $hasDefault = false,
        public bool $variadic = false,
        public bool $byReference = false,
    ) {}
}
