<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final class TemplateT implements Type
{
    /** @var non-empty-string */
    public readonly string $name;

    public readonly Variance $variance;

    public readonly Type $upperBound;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param non-empty-string $name
     */
    public function __construct(string $name, Variance $variance, Type $upperBound)
    {
        $this->name = $name;
        $this->variance = $variance;
        $this->upperBound = $upperBound;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->template($this);
    }
}
