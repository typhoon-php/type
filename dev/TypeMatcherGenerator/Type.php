<?php

declare(strict_types=1);

namespace Typhoon\Type\TypeMatcherGenerator;

final class Type
{
    /**
     * @param list<Param> $params
     */
    public function __construct(
        public readonly string $name,
        public readonly array $params,
    ) {}
}
