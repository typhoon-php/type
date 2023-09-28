<?php

declare(strict_types=1);

namespace Typhoon\Type\ThisTest;

use Typhoon\Type\ThisType;
use function Typhoon\Type\extractType;

/**
 * @param ThisType<\stdClass> $type
 */
function testThis(ThisType $type): void
{
    /** @psalm-check-type-exact $_class = \stdClass */
    $_class = extractType($type);
}
