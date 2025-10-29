<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\Type;

/**
 * @internal
 */
final readonly class Optional
{
    public function __construct(
        public Type $type,
    ) {}
}
