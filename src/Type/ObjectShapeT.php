<?php


declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 * @template T of object = object
 * @implements Type<T>
 * @codeCoverageIgnore
 */
final readonly class ObjectShapeT implements Type
{
    /**
     * @param list<Property> $properties
     */
    public function __construct(
        public array $properties = [],
    ) {}

    #[\Override]
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->objectShapeT($this);
    }
}
