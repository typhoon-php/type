<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @template TVariance of Variance = Variance
 */
final readonly class Template
{
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
        public TemplateT $type = new TemplateT(),
    ) {}
}
