<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template TType = mixed
 */
interface Type
{
    /**
     * @template TResult
     * @param TypeVisitor<TResult> $visitor
     * @return TResult
     */
    public function accept(TypeVisitor $visitor): mixed;
}
