<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 */
final readonly class Parameter
{
    /**
     * @param ?non-empty-string $name
     */
    public function __construct(
        public Type $type,
        public bool $hasDefault = false,
        public bool $isPassedByReference = false,
        public bool $isVariadic = false,
        public ?string $name = null,
    ) {}
}
