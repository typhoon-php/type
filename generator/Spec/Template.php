<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

final readonly class Template
{
    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $of
     * @param ?non-empty-string $default
     */
    public function __construct(
        private string $name,
        private ?string $of,
        private ?string $default,
    ) {}

    public function declaration(): string
    {
        return \sprintf(
            '@template-covariant %s%s%s',
            $this->name,
            $this->of === null ? '' : ' of ' . $this->of,
            $this->default === null ? '' : ' = ' . $this->default,
        );
    }
}

/**
 * @param non-empty-string $name
 * @param ?non-empty-string $of
 * @param ?non-empty-string $default
 */
function tpl(string $name, ?string $of = null, ?string $default = null): Template
{
    return new Template(
        name: $name,
        of: $of,
        default: $default ?? $of ?? 'mixed',
    );
}
