<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template-covariant T of int = int
 * @implements Type<T>
 */
final readonly class IntRangeT implements Type
{
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
    ) {
        if ($min !== null && $max !== null && $min > $max) {
            throw new \ValueError(\sprintf(
                '`%s` requires min to be less than or equal to max, got min=%d, max=%d',
                self::class,
                $min,
                $max,
            ));
        }
    }

    /**
     * @codeCoverageIgnore
     */
    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->intRangeT($this);
    }
}
