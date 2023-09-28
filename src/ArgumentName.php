<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 */
final class ArgumentName
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
    ) {}
}
