<?php

declare(strict_types=1);

namespace Typhoon\Type\IntMaskOfTest;

use Typhoon\Type\IntMaskOfType;
use Typhoon\Type\Type;
use function Typhoon\Type\extractType;

/**
 * @param Type<\ReflectionClass::IS_*> $constantType
 */
function a(Type $constantType): void
{
    /** @psalm-check-type $_int = 16|32|64|65536 */
    $_int = extractType(new IntMaskOfType($constantType));
}
