<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type\Internal\TermType;

/**
 * This code is generated, do not edit it.
 *
 * @api
 * @implements TermType<string>
 */
final class StringValueT implements TermType
{
    public readonly string $value;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->stringValue($this);
    }
}
