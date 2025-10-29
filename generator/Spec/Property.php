<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Nette\PhpGenerator\PromotedParameter;
use Typhoon\Type\Mask;
use Typhoon\Type\Type as TypeI;

final readonly class Property
{
    /**
     * @var non-empty-string
     */
    private string $nativeType;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     * @param ?non-empty-string $nativeType
     */
    public function __construct(
        private string $name,
        private string $type,
        ?string $nativeType,
        private mixed $default,
    ) {
        $this->nativeType = $nativeType ?? self::guessNativeType($type);
    }

    /**
     * @return non-empty-string
     */
    public function paramPhpDoc(): string
    {
        return \sprintf('@param %s $%s', $this->type, $this->name);
    }

    public function promotedParameter(): PromotedParameter
    {
        $parameter = (new PromotedParameter($this->name))
            ->setReadOnly()
            ->setType($this->nativeType);

        if ($this->default !== null) {
            $parameter->setDefaultValue($this->default);
        } elseif (str_starts_with($this->type, '?') || str_contains($this->type, 'null')) {
            $parameter->setDefaultValue(null);
        } elseif (str_contains($this->type, 'bool')) {
            $parameter->setDefaultValue(false);
        } elseif (str_starts_with($this->type, 'list') || str_starts_with($this->type, 'array')) {
            $parameter->setDefaultValue([]);
        }

        return $parameter;
    }

    /**
     * @param non-empty-string $type
     * @return non-empty-string
     */
    private static function guessNativeType(string $type): string
    {
        $nullable = $type[0] === '?';

        if ($nullable) {
            return '?' . self::normalizeAsNative(substr($type, 1));
        }

        return implode('|', array_unique(array_map(self::normalizeAsNative(...), explode('|', $type))));
    }

    private static function normalizeAsNative(string $type): string
    {
        $type = explode('<', $type)[0];

        return match ($type) {
            'numeric-string', 'non-empty-string', 'class-string', 'lowercase-string' => 'string',
            'list', 'non-empty-list', 'non-empty-array' => 'array',
            'Type' => TypeI::class,
            'Mask' => Mask::class,
            default => $type,
        };
    }
}

/**
 * @param non-empty-string $name
 * @param non-empty-string $type
 * @param ?non-empty-string $nativeType
 */
function prop(string $name, string $type, mixed $default = null, ?string $nativeType = null): Property
{
    return new Property(
        name: $name,
        type: $type,
        nativeType: $nativeType,
        default: $default,
    );
}
