<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\FloatRangeT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<float>
 */
enum FloatT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var FloatRangeT */
        static $type = new FloatRangeT(null, null);

        return $visitor->floatRange($type);
    }
}
