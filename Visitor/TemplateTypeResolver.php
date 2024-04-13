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

    public function withArgument(TemplateId $id, Type $argument): self
    {
        $resolver = clone $this;
        $resolver->arguments[$id->toString()] = $argument;

        return $resolver;
    }

    public function template(Type $self, TemplateId $template): mixed
    {
        return $this->arguments[$template->toString()] ?? $self;
    }
}
