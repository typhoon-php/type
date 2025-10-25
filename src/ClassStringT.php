<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<class-string>
 */
final readonly class ClassStringT implements Type
{
    public function __construct(
        public Type $of,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classString($this);
    }
}
