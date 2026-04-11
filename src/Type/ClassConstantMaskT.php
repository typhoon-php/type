<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T = mixed
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ClassConstantMaskT implements Type
{
    /**
     * @param class-string $class
     */
    public function __construct(
        public string $class,
        public Mask $mask,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->classConstantMaskT($this);
    }
}
