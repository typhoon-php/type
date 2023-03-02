<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\php;
use ExtendedTypeSystem\Type;

/**
 * @psalm-api
 * @psalm-immutable
 * @template-covariant TType
 */
final class Parameter
{
    /**
     * @internal
     * @psalm-internal ExtendedTypeSystem
     * @param Type<TType> $type
     */
    public function __construct(
        public readonly Type $type = php::mixed,
        public readonly bool $default = false,
        public readonly bool $variadic = false,
    ) {
        \assert(!($default && $variadic), 'Parameter can be either default or variadic.');
    }
}
