<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type;

/**
 * @extends Fallback<null>
 */
final class FallbackReduced extends Fallback
{
    use Reduced;

    protected function fallback(Type $type): mixed
    {
        return null;
    }
}
