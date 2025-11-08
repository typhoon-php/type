<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template TVariance of Variance = Variance
 */
final readonly class Template
{
    /**
     * @template TNewVariance of Variance
     * @param non-empty-string $name
     * @param TNewVariance $variance
     * @param-out TemplateT $type
     * @return self<TNewVariance>
     */
    public static function new(
        string $name,
        Variance $variance = Variance::Invariant,
        Type $lowerBound = NeverT::T,
        Type $upperBound = MixedT::T,
        ?Type $default = null,
        mixed &$type = null,
    ): self {
        return new self(
            name: $name,
            variance: $variance,
            lowerBound: $lowerBound,
            upperBound: $upperBound,
            default: $default,
            type: $type = new TemplateT($name),
        );
    }

    /**
     * @template TNewVariance of Variance
     * @param non-empty-string $name
     * @param TNewVariance $variance
     * @param-out TemplateT $type
     * @return \Closure(Type $lowerBound =, Type $upperBound =, ?Type $default =): self<TNewVariance>
     */
    public static function factory(
        string $name,
        Variance $variance = Variance::Invariant,
        mixed &$type = null,
    ): \Closure {
        $type = new TemplateT($name);

        return static fn(
            Type $lowerBound = NeverT::T,
            Type $upperBound = MixedT::T,
            ?Type $default = null,
        ): self => new self(
            name: $name,
            variance: $variance,
            lowerBound: $lowerBound,
            upperBound: $upperBound,
            default: $default,
            type: $type,
        );
    }

    /**
     * @param non-empty-string $name
     * @param TVariance $variance
     */
    private function __construct(
        public string $name,
        public Variance $variance,
        public Type $lowerBound,
        public Type $upperBound,
        public ?Type $default,
        public TemplateT $type,
    ) {}
}
