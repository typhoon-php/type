<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @api
 */
final class StaticTypeResolver extends RecursiveTypeReplacer
{
    /**
     * @param ?non-empty-string $class
     */
    public function __construct(
        private readonly ?string $class,
        private readonly bool $final = false,
    ) {}

    public function static(Type $self, string $class, array $arguments): mixed
    {
        if ($this->class === null) {
            return types::anonymousClassSelf(...$this->processTypes($arguments));
        }

        if ($this->final) {
            return types::object($this->class, ...$this->processTypes($arguments));
        }

        return types::static($this->class, ...$this->processTypes($arguments));
    }
}
