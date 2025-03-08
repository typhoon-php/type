<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Alias\MixedT;

/**
 * @api
 * @template TType = mixed
 */
final class Parameter
{
    /**
     * @var Type<TType>
     */
    public readonly Type $type;

    public readonly bool $hasDefault;

    public readonly bool $variadic;

    public readonly bool $byReference;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param Type<TType> $type
     */
    public function __construct(
        Type $type = MixedT::T,
        bool $hasDefault = false,
        bool $variadic = false,
        bool $byReference = false,
    ) {
        /** @phpstan-ignore assign.propertyType */
        $this->type = $type;
        $this->hasDefault = $hasDefault;
        $this->variadic = $variadic;
        $this->byReference = $byReference;
    }
}
