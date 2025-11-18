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
        public string $mask,
    ) {
        $this->pattern = \sprintf('/^%s$/D', str_replace('\*', '.*', preg_quote($mask, '/')));
    }

    public function test(string $name): bool
    {
        return preg_match($this->pattern, $name) === 1;
    }
}
