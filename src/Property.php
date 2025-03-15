<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final class Property
{
    public readonly Type $type;

    public readonly bool $optional;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(Type $type, bool $optional)
    {
        $this->optional = $optional;
        $this->type = $type;
    }

    public function with(?Type $type = null, ?bool $optional = null): self
    {
        return new self(
            type: $type ?? $this->type,
            optional: $optional ?? $this->optional,
        );
    }
}
