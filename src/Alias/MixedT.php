<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\FalseT;
use Typhoon\Type\NullT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\ResourceT;
use Typhoon\Type\StringT;
use Typhoon\Type\TrueT;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use Typhoon\Type\UnionT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<mixed>
 */
enum MixedT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var UnionT */
        static $type = new UnionT([
            NullT::T,
            TrueT::T,
            FalseT::T,
            IntT::T,
            FloatT::T,
            StringT::T,
            ArrayT::T,
            new ObjectT([]),
            ResourceT::T,
        ]);

        return $visitor->union($type);
    }
}
