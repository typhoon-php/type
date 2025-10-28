<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Closure;

/**
 * @api
 * @template-covariant T of Closure = Closure
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ClosureT implements Type
{
    /**
     * @param list<Template<Variance::Invariant>> $templates
     * @param list<Parameter> $parameters
     */
    public function __construct(
        public array $templates = [],
        public array $parameters = [],
        public Type $return = MixedT::T,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->closureT($this);
    }
}
