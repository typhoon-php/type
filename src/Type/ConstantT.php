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
final readonly class ConstantT implements Type
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->constantT($this);
    }

    public function evaluate(): mixed
    {
        return \constant($this->name);
    }
}
