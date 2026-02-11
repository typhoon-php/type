<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Typhoon\Type as TypeI;
use Typhoon\Type\Visitor;

final readonly class Type
{
    /**
     * @var non-empty-string
     */
    public string $name;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $type
     * @param list<Template> $templates
     * @param list<Property> $properties
     * @param ?non-empty-string $reduced
     */
    public function __construct(
        private bool $singleton,
        string $name,
        private string $type,
        private array $templates,
        private array $properties,
        private ?string $reduced,
    ) {
        $this->name = $name . 'T';
    }

    /**
     * @return non-empty-string
     */
    private function shortClassName(): string
    {
        return ucfirst($this->name);
    }

    /**
     * @return non-empty-string
     */
    private function className(): string
    {
        return 'Typhoon\Type\\' . $this->shortClassName();
    }

    public function class(): ClassType|EnumType
    {
        if ($this->singleton) {
            $enum = (new EnumType($this->shortClassName()))
                ->setComment(
                    <<<PHPDOC
                        @api
                        @implements Type<{$this->type}>
                        @codeCoverageIgnore
                        PHPDOC,
                )
                ->addImplement(TypeI::class);
            $enum->addCase('T');
            $enum->addMethod('accept')
                ->addAttribute(\Override::class)
                ->setParameters([(new Parameter('visitor'))->setType(Visitor::class)])
                ->setReturnType('mixed')
                ->setBody("return \$visitor->{$this->name}(\$this);");

            return $enum;
        }

        $class = (new ClassType($this->shortClassName()))
            ->addComment('@api')
            ->addComment(implode("\n", array_map(static fn(Template $t) => $t->declaration(), $this->templates)))
            ->addComment("@implements Type<{$this->type}>")
            ->addComment('@codeCoverageIgnore')
            ->setFinal()
            ->setReadOnly()
            ->addImplement(TypeI::class);

        if ($this->properties !== []) {
            $constructor = $class->addMethod('__construct');
            $constructorParams = [];

            foreach ($this->properties as $property) {
                $constructor->addComment($property->paramPhpDoc());
                $constructorParams[] = $property->promotedParameter();
            }

            $constructor->setParameters($constructorParams);
        }

        $class
            ->addMethod('accept')
            ->addAttribute(\Override::class)
            ->setParameters([(new Parameter('visitor'))->setType(Visitor::class)])
            ->setReturnType('mixed')
            ->setBody(\sprintf('return $visitor->%s($this);', $this->name));

        return $class;
    }

    public function visitorMethod(): Method
    {
        return (new Method($this->name))
            ->setComment('@return TResult')
            ->setPublic()
            ->setParameters([(new Parameter('type'))->setType($this->className())])
            ->setReturnType('mixed');
    }

    public function isReducible(): bool
    {
        return $this->reduced !== null;
    }

    /**
     * @param list<Type> $types
     */
    public function reducedMethod(array $types): Method
    {
        return (new Method($this->name))
            ->addAttribute(\Override::class)
            ->setPublic()
            ->setParameters([(new Parameter('type'))->setType($this->className())])
            ->setReturnType('mixed')
            ->setBody($this->reducedBody($types));
    }

    /**
     * @param list<Type> $types
     * @return non-empty-string
     */
    private function reducedBody(array $types): string
    {
        if ($this->reduced === null) {
            throw new \LogicException(\sprintf('Type `%s` is not reducible', $this->name));
        }

        $typesByName = array_column($types, null, 'name');

        /** @var ?self */
        $firstType = null;

        $code = preg_replace_callback(
            '/(?<!:)\$?\w++/',
            static function (array $matches) use ($typesByName, &$firstType): string {
                $match = $matches[0];

                if ($match === '$t') {
                    return '$type';
                }

                if ($match[0] === '$') {
                    return '$type->' . substr($match, 1);
                }

                $type = $typesByName[$match . 'T'] ?? null;

                if ($type === null) {
                    return $match;
                }

                $firstType ??= $type;

                if ($type->singleton) {
                    return '\\' . $type->className() . '::T';
                }

                return 'new \\' . $type->className();
            },
            $this->reduced,
        );

        if ($code === null || $firstType === null) {
            throw new \LogicException($this->name);
        }

        if ($firstType->singleton || str_contains($code, '$')) {
            return "return \$this->{$firstType->name}({$code});";
        }

        $unionFix = '';

        if (str_contains($code, 'UnionT([')) {
            $unionFix = ' // @phpstan-ignore argument.type';
        }

        return <<<PHP
            /** @var \\{$firstType->className()} */
            static \$reduced = {$code};{$unionFix}
            
            return \$this->{$firstType->name}(\$reduced);
            PHP;
    }

    public function fallbackMethod(): Method
    {
        return (new Method($this->name))
            ->addAttribute(\Override::class)
            ->setPublic()
            ->setParameters([(new Parameter('type'))->setType($this->className())])
            ->setReturnType('mixed')
            ->setBody('return $this->fallback($type);');
    }
}

/**
 * @param non-empty-string $name
 * @param non-empty-string $type
 * @param ?non-empty-string $reduced
 */
function single(string $name, string $type, ?string $reduced = null): Type
{
    return new Type(
        singleton: true,
        name: $name,
        type: $type,
        templates: [],
        properties: [],
        reduced: $reduced,
    );
}

/**
 * @param non-empty-string $name
 * @param non-empty-string $type
 * @param list<Template> $templates
 * @param list<Property> $properties
 * @param ?non-empty-string $reduced
 */
function constr(string $name, string $type, array $templates = [], array $properties = [], ?string $reduced = null): Type
{
    return new Type(
        singleton: false,
        name: $name,
        type: $type,
        templates: $templates,
        properties: $properties,
        reduced: $reduced,
    );
}
