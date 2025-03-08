<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Typhoon\Type\Type;
use Typhoon\Type\Variance;

final class TypeSpec
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     * @param list<TemplateSpec> $templates
     * @param list<PropertySpec> $properties
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly array $templates = [],
        public readonly array $properties = [],
    ) {}

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
        );
    }

    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     */
    public function prop(string $name, string $type): self
    {
        return new self(
            name: $this->name,
            type: $this->type,
            templates: $this->templates,
            properties: [
                ...$this->properties,
                new PropertySpec($name, $type),
            ],
        );
    }
}

final class TemplateSpec
{
    /**
     * @param non-empty-string $name
     * @param ?non-empty-string $of
     * @param ?non-empty-string $default
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $of = null,
        public readonly ?string $default = null,
    ) {}
}

final class PropertySpec
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
    ) {}

    public function nativeType(): string
    {
        $container = explode('<', $this->type)[0];
        $nullable = $container[0] === '?';

        if ($nullable) {
            $container = substr($container, 1);
        }

        return ($nullable ? '?' : '') . match ($container) {
            'Variance' => Variance::class,
            'Type' => Type::class,
            'list', 'non-empty-list' => 'array',
            'non-empty-string', 'class-string' => 'string',
            default => $container,
        };
    }
}

/**
 * @param non-empty-string $name
 * @param non-empty-string $type
 */
function type(string $name, string $type): TypeSpec
{
    return new TypeSpec($name, $type);
}
