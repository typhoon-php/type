<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<object>
 */
final readonly class SelfT implements Type
{
    /**
     * @param list<Type> $templateArguments
     */
    public function __construct(
        public array $templateArguments = [],
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->self($this);
    }
}
