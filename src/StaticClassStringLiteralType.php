<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 * @implements Type<class-string>
 * Absence of a template declaration for the object type is intentional. Consider a trait: static::class type declared in
 * this trait does not represent class of the trait, but an instance of a class that uses this trait.
 */
final class StaticClassStringLiteralType implements Type
{
    /**
     * @var class-string
     */
    public readonly string $declaredAtClass;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param class-string $declaredAtClass
     */
    public function __construct(
        string $declaredAtClass,
    ) {
        $this->declaredAtClass = $declaredAtClass;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitStaticClassStringLiteral($this);
    }
}
