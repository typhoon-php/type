<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class AliasAtFunctionT implements Type
{
    /**
     * @param non-empty-string $function
     * @param non-empty-string $name
     */
    public function __construct(
        public string $function,
        public string $name,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->aliasAtFunctionT($this);
    }
}
