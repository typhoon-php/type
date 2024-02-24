<?php

declare(strict_types=1);

namespace Typhoon\Type\TypeMatcherGenerator;

final class TypeMatcherGenerator
{
    /**
     * @param list<Type> $types
     */
    public function generate(array $types): string
    {
        return <<<PHP
            <?php
            
            declare(strict_types=1);
            
            namespace Typhoon\\Type;

            /**
             * @api
             * @template-covariant TReturn
             * @implements TypeVisitor<TReturn>
             */
            final class TypeMatcher implements TypeVisitor
            {
            {$this->properties($types)}

                /**
            {$this->constructorPhpDocParams($types)}
                 * @param ?\\Closure(): TReturn \$default
                 */
                public function __construct(
            {$this->constructorParams($types)}
                    ?\\Closure \$default = null,
                ) {
            {$this->constructorPropertyAssignments($types)}
                }
            
            {$this->methods($types)}
            }

            PHP;
    }

    /**
     * @param list<Type> $types
     */
    private function constructorParams(array $types): string
    {
        return implode("\n", array_map(
            static fn(Type $type): string => "        ?\\Closure \${$type->name} = null,",
            $types,
        ));
    }

    /**
     * @param list<Type> $types
     */
    private function constructorPhpDocParams(array $types): string
    {
        return implode("\n", array_map(
            fn(Type $type): string => "     * @param ?{$this->phpDocClosure($type)} \${$type->name}",
            $types,
        ));
    }

    /**
     * @param list<Type> $types
     */
    private function constructorPropertyAssignments(array $types): string
    {
        return implode("\n", array_map(
            static fn(Type $type): string => "        \$this->{$type->name} = \${$type->name} ?? \$default ?? throw new \\InvalidArgumentException('Either \${$type->name} or \$default must be provided.');",
            $types,
        ));
    }

    /**
     * @param list<Type> $types
     */
    private function methods(array $types): string
    {
        return implode("\n\n", array_map(
            static function (Type $type): string {
                $params = implode(', ', array_map(
                    static fn(Param $param): string => $param->native(),
                    $type->params,
                ));
                $args = implode(', ', array_map(
                    static fn(Param $param): string => $param->arg(),
                    $type->params,
                ));

                return <<<PHP
                        public function {$type->name}({$params}): mixed
                        {
                            return \$this->{$type->name}->__invoke({$args}, \$this);
                        }
                    PHP;
            },
            $types,
        ));
    }

    private function phpDocClosure(Type $type): string
    {
        return sprintf('\Closure(%s, TypeVisitor): TReturn', implode(', ', array_map(
            static fn(Param $param): string => $param->phpDoc(),
            $type->params,
        )));
    }

    /**
     * @param list<Type> $types
     */
    private function properties(array $types): string
    {
        return implode("\n\n", array_map(
            fn(Type $type): string => <<<PHP
                    /** @var {$this->phpDocClosure($type)} */
                    private readonly \\Closure \${$type->name};
                PHP,
            $types,
        ));
    }
}
