<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @template TVariance of Variance = Variance
 */
final readonly class Template
{
    public TemplateT $type;

    /**
     * @param non-empty-string $name
     * @param TVariance $variance
     */
    public function __construct(
        public string $name,
        public Variance $variance = Variance::Invariant,
        public Type $lowerBound = NeverT::T,
        public Type $upperBound = MixedT::T,
        public ?Type $default = null,
    ) {
        $this->type = new TemplateT();
    }

    public function withLowerBound(Type $lowerBound): static
    {
        return new self(
            name: $this->name,
            variance: $this->variance,
            lowerBound: $lowerBound,
            upperBound: $this->upperBound,
            default: $this->default,
        );
    }

    public function withUpperBound(Type $upperBound): static
    {
        return new self(
            name: $this->name,
            variance: $this->variance,
            lowerBound: $this->lowerBound,
            upperBound: $upperBound,
            default: $this->default,
        );
    }

    public function withDefault(?Type $default): static
    {
        return new self(
            name: $this->name,
            variance: $this->variance,
            lowerBound: $this->lowerBound,
            upperBound: $this->upperBound,
            default: $default,
        );
    }
}
