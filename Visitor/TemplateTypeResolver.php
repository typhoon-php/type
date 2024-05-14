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
    private array $arguments = [];

    /**
     * @param iterable<array{TemplateId, Type}> $arguments
     */
    public function __construct(iterable $arguments)
    {
        $resolved = [];

        foreach ($arguments as [$templateId, $type]) {
            $resolved[$templateId->toString()] = $type;
        }

        $this->arguments = $resolved;
    }

    public function template(Type $self, TemplateId $template): mixed
    {
        return $this->arguments[$template->toString()] ?? $self;
    }
}
