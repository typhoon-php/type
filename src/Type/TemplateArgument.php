<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 */
final readonly class TemplateArgument
{
    public function __construct(
        public Type $type,
        public ?Variance $variance = null,
    ) {}
}
