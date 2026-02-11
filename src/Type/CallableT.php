<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of callable = callable
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class CallableT implements Type
{
    /**
     * @param list<Parameter> $parameters
     */
    public function __construct(
        public array $parameters = [],
        public Type $returnType = MixedT::T,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->callableT($this);
    }
}
