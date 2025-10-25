<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template TType = mixed
 * @extends Type<TType>
 */
interface Shortcut extends Type
{
    /**
     * @return Type<TType>
     */
    public function dereference(): Type;
}
