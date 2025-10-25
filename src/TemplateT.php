<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * This class is generated, do not edit it.
 *
 * @api
 * @implements Type<mixed>
 */
final readonly class TemplateT implements Type
{
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->template($this);
    }
}
