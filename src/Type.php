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
     * @param Visitor<TResult> $visitor
     * @return TResult
     */
    public function accept(Visitor $visitor): mixed;
}
