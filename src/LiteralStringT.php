<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<literal-string>
 */
enum LiteralStringT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        /** @var LiteralT */
        static $type = new LiteralT(StringT::T);

        return $visitor->literalT($type);
    }
}
