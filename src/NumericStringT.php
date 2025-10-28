<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @implements Type<numeric-string>
 * @codeCoverageIgnore
 */
enum NumericStringT implements Type
{
    case T;

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->numericStringT($this);
    }
}
