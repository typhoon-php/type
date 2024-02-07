<?php

declare(strict_types=1);

namespace Typhoon\Type;

/**
 * @api
 * @psalm-immutable
 * @implements Type<object>
 * Absence of a template declaration for the object type is intentional. Consider a trait: parent type declared in
 * this trait does not represent instance of the trait, but an instance of a class that uses this trait.
 */
final class ParentType implements Type
{
    /**
     * @var class-string
     */
    public readonly string $declaredAtClass;

    /**
     * @var list<Type>
     */
    public readonly array $templateArguments;

    /**
     * @internal
     * @psalm-internal Typhoon\Type
     * @param class-string $declaredAtClass
     * @param list<Type> $templateArguments
     */
    public function __construct(
        string $declaredAtClass,
        array $templateArguments = [],
    ) {
        $this->declaredAtClass = $declaredAtClass;
        $this->templateArguments = $templateArguments;
    }

    public function accept(TypeVisitor $visitor): mixed
    {
        return $visitor->visitParent($this);
    }
}
