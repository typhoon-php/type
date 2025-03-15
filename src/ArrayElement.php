<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 */
final class ArrayElement
{
    public readonly Type $type;

    public readonly bool $optional;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     */
    public function __construct(Type $type, bool $optional)
    {
        $this->type = $type;
        $this->optional = $optional;
    }

    public function withType(Type $type): self
    {
        return new self($type, $this->optional);
    }
}
