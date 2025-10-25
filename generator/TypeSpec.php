<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Typhoon\Type\Type;

final readonly class TypeSpec
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     * @param list<TemplateSpec> $templates
     * @param list<PropertySpec> $properties
     */
    public function __construct(
        public string $name,
        public string $type,
        public array $templates = [],
        public array $properties = [],
        public bool $class = false,
    ) {}

    public function phpstanType(): string
    {
        return match ($this->type) {
            'array' => 'array<mixed>',
            'iterable' => 'iterable<mixed>',
            default => $this->type,
        };
    }

    /**
     * @return non-empty-string
     */
    public function shortClassName(): string
    {
        return ucfirst($this->name) . 'T';
    }

    /**
     * @return non-empty-string
     */
    public function className(): string
    {
        return 'Typhoon\Type\\' . $this->shortClassName();
    }

    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $of
     * @param ?non-empty-string $default
     */
    public function tpl(string $name, ?string $of = null, ?string $default = null): self
    {
        return new self(
            name: $this->name,
            type: $this->type,
            templates: [
                ...$this->templates,
                new TemplateSpec($name, $of, $default),
            ],
            properties: $this->properties,
            class: $this->class,
        );
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     */
    public function prop(string $name, string $type, mixed $default = null): self
    {
        return new self(
            name: $this->name,
            type: $this->type,
            templates: $this->templates,
            properties: [
                ...$this->properties,
                new PropertySpec($name, $type, $default),
            ],
            class: $this->class,
        );
    }
}

final readonly class TemplateSpec
{
    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $of
     * @param ?non-empty-string $default
     */
    public function __construct(
        public string $name,
        public ?string $of = null,
        public ?string $default = null,
    ) {}
}

final readonly class PropertySpec
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     */
    public function __construct(
        public string $name,
        public string $type,
        public mixed $default,
    ) {}

    /**
     * @return array{bool, mixed}
     */
    public function default(): array
    {
        return match (true) {
            $this->default !== null => [true, $this->default],
            str_starts_with($this->type, '?'), str_contains($this->type, 'null') => [true, null],
            str_contains($this->type, 'bool') => [true, false],
            str_starts_with($this->type, 'list'), str_starts_with($this->type, 'array') => [true, []],
            default => [false, null],
        };
    }

    public function nativeType(): string
    {
        $type = $this->type;
        $nullable = $type[0] === '?';

        if ($nullable) {
            return '?' . self::normalizeOne(substr($type, 1));
        }

        return implode('|', array_unique(array_map(self::normalizeOne(...), explode('|', $type))));
    }

    private static function normalizeOne(string $type): string
    {
        $type = explode('<', $type)[0];

        return match ($type) {
            'numeric-string', 'non-empty-string', 'class-string', 'lowercase-string' => 'string',
            'list', 'non-empty-list' => 'array',
            'Type' => Type::class,
            default => $type,
        };
    }
}

/**
 * @param non-empty-string $name
 * @param non-empty-string $type
 */
function type(string $name, string $type, bool $class = false): TypeSpec
{
    return new TypeSpec($name, $type, class: $class);
}
