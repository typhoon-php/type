<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final class Parameter
{
    public readonly Type $type;

    public readonly bool $hasDefault;

    public readonly bool $variadic;

    public readonly bool $byReference;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(Type $type, bool $hasDefault, bool $variadic, bool $byReference)
    {
        $this->type = $type;
        $this->hasDefault = $hasDefault;
        $this->variadic = $variadic;
        $this->byReference = $byReference;
    }
}
