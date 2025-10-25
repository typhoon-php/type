<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<callable>
 */
final readonly class CallableT implements Type
{
    /**
     * @param list<Template<Variance::Invariant>> $templates
     * @param list<Parameter> $parameters
     */
    public function __construct(
        public array $templates = [],
        public array $parameters = [],
        public Type $returns = MixedT::T,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->callable($this);
    }
}
