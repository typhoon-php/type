<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class Parameter
{
    /**
     * @param ?non-empty-string $name
     */
    public function __construct(
        public ?string $name = null,
        public Type $type = NeverT::T,
        public bool $hasDefault = false,
        public ?Type $defaultType = null,
        public bool $isPassedByReference = false,
        public ?Type $outType = null,
        public bool $isVariadic = false,
    ) {}
}
