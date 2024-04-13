<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 */
enum At
{
    case anonymousClass;
    case anonymousFunction;
}
