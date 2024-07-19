<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\DeclarationId\TemplateId;
use Typhoon\Type\Type;

/**
 * @api
 * @readonly
 */
final class TemplateTypeResolver extends RecursiveTypeReplacer
{
    /**
     * @var array<non-empty-string, Type>
     */
    private readonly array $typeArguments;

    /**
     * @param iterable<array{TemplateId, Type}> $typeArguments
     */
    public function __construct(iterable $typeArguments)
    {
        $map = [];

        foreach ($typeArguments as [$templateId, $type]) {
            $map[$templateId->encode()] = $type;
        }

        $this->typeArguments = $map;
    }

    public function template(Type $type, TemplateId $templateId): mixed
    {
        return $this->typeArguments[$templateId->encode()] ?? $type;
    }
}
