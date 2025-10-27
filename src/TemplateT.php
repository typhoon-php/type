<?php

/**
 * @generated This file was generated, do not edit manually.
 */

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @template-covariant T = mixed
 * @implements Type<T>
 */
final readonly class TemplateT implements Type
{
    public function accept(Visitor $visitor): mixed
    {
        return $visitor->templateT($this);
    }
}
