<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class Property
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public Type $type = MixedT::T,
        public bool $isOptional = false,
    ) {}
}
