<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use Typhoon\Type\Type;

/**
 * @api
 */
final class AnonymousClassTypesResolver extends RecursiveTypeReplacer
{
    private function __construct(
        public readonly Type $objectType,
    ) {}
}
