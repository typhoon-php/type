<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\FalseT;
use Typhoon\Type\FloatRangeT;
use Typhoon\Type\IntRangeT;
use Typhoon\Type\StringT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\UnionT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<scalar>
 */
enum ScalarT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var UnionT */
        static $type = new UnionT([
            FalseT::T,
            TrueT::T,
            new IntRangeT(null, null),
            new FloatRangeT(null, null),
            StringT::T,
        ]);

        return $visitor->union($type);
    }
}
