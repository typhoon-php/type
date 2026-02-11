<?php

declare(strict_types=1);

namespace Typhoon;

use Typhoon\Type\Visitor;

/**
 * @api
 * @template T = mixed
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
