<?php

declare(strict_types=1);

namespace Typhoon\Type\TypeMatcherGenerator;

use Typhoon\Type\TypeVisitor;

final class TypeVisitorParser
{
    /**
     * @return list<Type>
     */
    public function parse(): array
    {
        $types = [];
        $class = new \ReflectionClass(TypeVisitor::class);

        foreach ($class->getMethods() as $method) {
            $phpDoc = $method->getDocComment() === false ? '' : $method->getDocComment();
            $types[] = new Type($method->name, array_map(
                fn(\ReflectionParameter $parameter): Param => new Param(
                    $parameter->name,
                    $this->nativeType($parameter->getType() ?? throw new \ReflectionException()),
                    $this->phpDocParamType($phpDoc, $parameter->name),
                ),
                $method->getParameters(),
            ));
        }

        return $types;
    }

    private function nativeType(\ReflectionType $type): string
    {
        if ($type instanceof \ReflectionUnionType) {
            return implode('|', array_map($this->nativeType(...), $type->getTypes()));
        }

        if ($type instanceof \ReflectionIntersectionType) {
            return implode('&', array_map($this->nativeType(...), $type->getTypes()));
        }

        if (!$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException();
        }

        $name = $type->getName();
        $lastSlash = strrpos($name, '\\');
        $name = substr($name, $lastSlash === false ? 0 : $lastSlash + 1);

        if ($type->allowsNull() && $name !== 'null' && $name !== 'mixed') {
            $name = '?' . $name;
        }

        return $name;
    }

    private function phpDocParamType(string $phpDoc, string $name): ?string
    {
        preg_match("/@param (.+) \\\${$name}\\s/", $phpDoc, $matches);

        return $matches[1] ?? null;
    }
}
