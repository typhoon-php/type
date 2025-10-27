<?php

declare(strict_types=1);

namespace Typhoon\Type;

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
    ) {
        $this->type = new TemplateT();
    }
}
