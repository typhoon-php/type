<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

/**
 * @internal this class is used in {@see Stringify} to avoid `if`
 * @template-covariant T of ?object
 */
final readonly class Reference
{
    /**
     * @param T $object
     */
    public function __construct(
        private ?object $object = null,
    ) {}

    /**
     * @return T
     */
    public function get(): ?object
    {
        return $this->object;
    }
}
