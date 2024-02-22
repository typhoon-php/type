<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final class AtFunction
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
    ) {}
}
