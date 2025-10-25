<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class ClassConstantT implements Type
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public Type $on,
        public string $name,
    ) {}

    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classConstant($this);
    }
}
