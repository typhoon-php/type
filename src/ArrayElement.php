<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Alias\MixedT;

/**
 * @api
 * @template TType = mixed
 */
final class ArrayElement
{
    /**
     * @var Type<TType>
     */
    public readonly Type $type;

    public readonly bool $optional;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param Type<TType> $type
     */
    public function __construct(
        Type $type = MixedT::T,
        bool $optional = false,
    ) {
        /** @phpstan-ignore assign.propertyType */
        $this->type = $type;
        $this->optional = $optional;
    }
}
