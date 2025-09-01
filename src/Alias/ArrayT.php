<?php

declare(strict_types=1);

namespace Typhoon\Type\Alias;

use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;
use const Typhoon\Type\arrayKeyT;
use const Typhoon\Type\mixedT;

/**
 * @internal
 * @psalm-internal Typhoon\Type
 * @implements Type<array>
 */
enum ArrayT implements Type
{
    case T;

    public function accept(TypeVisitor $visitor): mixed
    {
        /** @var \Typhoon\Type\ArrayT */
        static $type = new \Typhoon\Type\ArrayT(
            nonEmpty: false,
            keyType: arrayKeyT,
            valueType: mixedT,
            elements: [],
        );

        return $visitor->array($type);
    }
}
