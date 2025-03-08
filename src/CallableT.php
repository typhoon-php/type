<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<callable>
 */
final class CallableT implements Type
{
    /** @var list<TemplateT> */
    public readonly array $templates;

    /** @var list<Parameter> */
    public readonly array $parameters;

    /** @var Type<mixed> */
    public readonly Type $returnType;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param list<TemplateT> $templates
     * @param list<Parameter> $parameters
     * @param Type<mixed> $returnType
     */
    public function __construct(array $templates, array $parameters, Type $returnType)
    {
        $this->templates = $templates;
        $this->parameters = $parameters;
        $this->returnType = $returnType;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->callable($this);
    }
}
