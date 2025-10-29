<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final readonly class Mask
{
    /**
     * @var non-empty-string
     */
    private string $pattern;

    /**
     * @param non-empty-string $mask
     */
    public function __construct(
        private string $mask,
    ) {
        $this->pattern = \sprintf('/^%s$/D', str_replace('\*', '.*', preg_quote($mask)));
    }

    public function match(string $name): bool
    {
        return preg_match($this->pattern, $name) === 1;
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->mask;
    }
}
