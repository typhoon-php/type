<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class Mask
{
    /**
     * @param non-empty-string $mask
     */
    public function __construct(
        public string $mask,
    ) {}

    public function test(string $name): bool
    {
        return preg_match($this->pattern(), $name) === 1;
    }

    /**
     * @return non-empty-string
     */
    private function pattern(): string
    {
        return \sprintf('/^%s$/D', str_replace('\*', '.*', preg_quote($this->mask, '/')));
    }
}
