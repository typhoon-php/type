<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use Typhoon\Type\ClosureDefaultT;
use Typhoon\Type\ClosureT;
use Typhoon\Type\IntersectionT;
use Typhoon\Type\NamedObjectT;
use Typhoon\Type\ObjectT;
use Typhoon\Type\StringValueT;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor\Fallback;

/**
 * @internal
 * @extends Fallback<non-empty-list<class-string>>
 */
final class ResolveClasses extends Fallback
{
    public function stringValueT(StringValueT $type): mixed
    {
        return class_exists($type->value) ? [$type->value] : $this->fallback($type);
    }

    public function namedObjectT(NamedObjectT $type): mixed
    {
        return [$type->class];
    }

    public function closureDefaultT(ClosureDefaultT $type): mixed
    {
        return [\Closure::class];
    }

    public function closureT(ClosureT $type): mixed
    {
        return [\Closure::class];
    }

    public function objectT(ObjectT $type): mixed
    {
        if ($type->superTypes === []) {
            $this->fallback($type);
        }

        return array_column($type->superTypes, 'class');
    }

    public function intersectionT(IntersectionT $type): mixed
    {
        return array_merge(
            ...array_map(
                fn(Type $type): array => $type->accept($this),
                $type->types,
            ),
        );
    }

    protected function fallback(Type $type): never
    {
        throw new \LogicException();
    }
}
