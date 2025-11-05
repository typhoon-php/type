<?php

declare(strict_types=1);

namespace Typhoon\Type;

use Typhoon\Type;

/**
 * @api
 */
function fromReflection(?\ReflectionType $reflection): Type
{
    if ($reflection === null) {
        return UntypedT::T;
    }

    if ($reflection instanceof \ReflectionNamedType) {
        $name = $reflection->getName();

        $type = match ($name) {
            'null' => nullT,
            'void' => voidT,
            'never' => neverT,
            'false' => falseT,
            'true' => trueT,
            'bool' => boolT,
            'int' => intT,
            'float' => floatT,
            'string' => stringT,
            'array' => arrayT,
            'object' => objectT,
            'self' => selfT,
            'parent' => parentT,
            'static' => staticT,
            'iterable' => iterableT,
            'callable' => callableT,
            'mixed' => mixedT,
            default => (class_exists($name) || interface_exists($name))
                ? namedObjectT($name)
                : throw new \LogicException(\sprintf('Name `%s` is not a class', $name)),
        };

        if ($reflection->allowsNull() && $type !== nullT && $type !== mixedT) {
            return nullOrT($type);
        }

        return $type;
    }

    if ($reflection instanceof \ReflectionUnionType) {
        return unionT(
            array_values(
                array_map(
                    fromReflection(...),
                    $reflection->getTypes(),
                ),
            ),
        );
    }

    if ($reflection instanceof \ReflectionIntersectionType) {
        return intersectionT(
            array_values(
                array_map(
                    fromReflection(...),
                    $reflection->getTypes(),
                ),
            ),
        );
    }

    throw new \LogicException(\sprintf('`%s` is not supported', $reflection::class));
}
