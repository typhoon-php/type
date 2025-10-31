<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant V of Variance = Variance
 * @template-covariant T = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class TemplateT implements Type
{
    /**
     * @param non-empty-string $name
     * @param V $variance
     */
    public function __construct(
        public string $name,
        public Variance $variance = Variance::Invariant,
        public Type $lowerBound = NeverT::T,
        public Type $upperBound = MixedT::T,
        public ?Type $default = null,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->templateT($this);
    }
}
