<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<string>
 * @codeCoverageIgnore
 */
enum StringT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->stringT($this);
    }
}
