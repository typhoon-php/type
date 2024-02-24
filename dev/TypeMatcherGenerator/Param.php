<?php

declare(strict_types=1);

namespace Typhoon\Type\TypeMatcherGenerator;

final class Param
{
    public function __construct(
        public readonly string $name,
        public readonly string $nativeType,
        public readonly ?string $phpDocType,
    ) {}

    public function arg(): string
    {
        return '$' . $this->name;
    }

    public function native(): string
    {
        return sprintf('%s $%s', $this->nativeType, $this->name);
    }

    public function phpDoc(): string
    {
        return sprintf('%s', $this->phpDocType ?? $this->nativeType);
    }
}
