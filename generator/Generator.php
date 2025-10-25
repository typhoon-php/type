<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Finder\Finder;
use Typhoon\Type\Shortcut;
use Typhoon\Type\TemplateT;
use Typhoon\Type\Type;
use Typhoon\Type\Visitor;

final readonly class Generator
{
    private const GENERATED_NOTICE = 'This class is generated, do not edit it.';
    private const NAMESPACE = 'Typhoon\Type';

    public static function generate(): void
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        /** @var non-empty-list<TypeSpec> */
        $types = require __DIR__ . '/types.php';

        $generator = new self(
            types: $types,
            srcDir: __DIR__ . '/../src',
        );
        $generator->cleanUp();
        $generator->generateTypes();
        $generator->generateVisitor();
        $generator->generateFallbackVisitor();
        $generator->updateStringifyVisitor();
    }

    /**
     * @param non-empty-list<TypeSpec> $types
     * @param non-empty-string $srcDir
     */
    private function __construct(
        private array $types,
        private string $srcDir,
    ) {}

    private function cleanUp(): void
    {
        $files = Finder::create()
            ->in($this->srcDir)
            ->name('*.php')
            ->contains(self::GENERATED_NOTICE);

        foreach ($files as $file) {
            unlink($file->getPathname());
        }
    }

    private function generateTypes(): void
    {
        foreach ($this->types as $type) {
            if ($type->properties === [] && !$type->class) {
                $this->writeClass($this->generateAtomicType($type));
            } else {
                $this->writeClass($this->generateComplexType($type));
            }
        }
    }

    private function generateAtomicType(TypeSpec $type): EnumType
    {
        $enum = (new EnumType($type->shortClassName()))
            ->setComment(self::GENERATED_NOTICE . "\n\n@api\n@implements Type<{$type->type}>")
            ->addImplement(Type::class);
        $enum->addCase('T');
        $enum->addMethod('accept')
            ->setReturnType('mixed')
            ->addBody("return \$visitor->{$type->name}(\$this);")
            ->addParameter('visitor')->setType(Visitor::class);

        return $enum;
    }

    private function generateComplexType(TypeSpec $typeSpec): ClassType
    {
        $typeComment = self::GENERATED_NOTICE . "\n\n@api";

        foreach ($typeSpec->templates as $templateSpec) {
            $typeComment .= \sprintf(
                "\n@template %s%s%s",
                $templateSpec->name,
                $templateSpec->of === null ? '' : ' of ' . $templateSpec->of,
                $templateSpec->default === null ? '' : ' = ' . $templateSpec->default,
            );
        }

        $typeComment .= "\n@implements Type<{$typeSpec->phpstanType()}>";

        $class = (new ClassType($typeSpec->shortClassName()))
            ->setFinal()
            ->setReadOnly()
            ->setComment($typeComment)
            ->addImplement(Type::class);

        if ($typeSpec->properties !== []) {
            $constructorComment = '';
            $constructorParams = [];

            foreach ($typeSpec->properties as $propertySpec) {
                $constructorComment .= \sprintf("\n@param %s $%s", $propertySpec->type, $propertySpec->name);
                $parameter = (new PromotedParameter($propertySpec->name))
                    ->setReadOnly()
                    ->setType($propertySpec->nativeType());

                [$hasDefault, $defaultValue] = $propertySpec->default();

                if ($hasDefault) {
                    $parameter->setDefaultValue($defaultValue);
                }

                $constructorParams[] = $parameter;
            }

            $class
                ->addMethod('__construct')
                ->setComment($constructorComment)
                ->setParameters($constructorParams);
        }

        $class
            ->addMethod('accept')
            ->setReturnType('mixed')
            ->addBody(\sprintf('return $visitor->%s($this);', $typeSpec->name))
            ->addParameter('visitor')->setType(Visitor::class);

        return $class;
    }

    private function generateVisitor(): void
    {
        $visitor = (new InterfaceType('Visitor'))
            ->setComment(self::GENERATED_NOTICE . "\n\n@api\n@template-covariant TResult");

        foreach ($this->types as $type) {
            $visitor
                ->addMethod($type->name)
                ->setPublic()
                ->setReturnType('mixed')
                ->setComment('@return TResult')
                ->addParameter('type')->setType($type->className());
        }

        $visitor
            ->addMethod('shortcut')
            ->setPublic()
            ->setReturnType('mixed')
            ->setComment('@return TResult')
            ->addParameter('type')->setType(Shortcut::class);

        $this->writeClass($visitor);
    }

    private function generateFallbackVisitor(): void
    {
        $visitor = (new ClassType('Fallback'))
            ->setAbstract()
            ->addImplement(Visitor::class)
            ->setComment(self::GENERATED_NOTICE . "\n\n@api\n@template-covariant TResult\n@implements Visitor<TResult>");

        foreach ($this->types as $type) {
            $visitor
                ->addMethod($type->name)
                ->setPublic()
                ->setReturnType('mixed')
                ->setBody('return $this->fallback($type);')
                ->addParameter('type')->setType($type->className());
        }

        $visitor
            ->addMethod('shortcut')
            ->setPublic()
            ->setReturnType('mixed')
            ->setBody('return $this->fallback($type);')
            ->addParameter('type')->setType(Shortcut::class);

        $visitor
            ->addMethod('fallback')
            ->setPublic()
            ->setAbstract()
            ->setReturnType('mixed')
            ->setComment('@return TResult')
            ->addParameter('type')->setType(Type::class);

        $this->writeClass($visitor, 'Visitor');
    }

    private function updateStringifyVisitor(): void
    {
        $code = file_get_contents($this->srcDir . '/Visitor/Stringify.php');
        \assert($code !== false);

        $existing = ClassType::fromCode($code);

        $new = (new ClassType('Stringify'))
            ->setAbstract()
            ->addImplement(Visitor::class)
            ->setComment("This class is partially generated, be careful when editing it.\n\n@api\n@implements Visitor<non-empty-string>")
            ->setConstants($existing->getConstants())
            ->setProperties($existing->getProperties());

        if ($existing->hasMethod('__construct')) {
            $new->addMember($existing->getMethod('__construct'));
        }

        foreach ($this->types as $type) {
            $method = $new
                ->addMethod($type->name)
                ->setPublic()
                ->setReturnType('string');

            $method->addParameter('type')->setType($type->className());

            if ($type->properties === [] && !$type->class) {
                $method->setBody("return '{$type->type}';");

                continue;
            }

            if ($existing->hasMethod($type->name)) {
                $method->setBody($existing->getMethod($type->name)->getBody());

                continue;
            }

            $method->setBody("// todo\nreturn '{$type->name}';");
        }

        $new
            ->addMethod('shortcut')
            ->setPublic()
            ->setReturnType('string')
            ->setBody($existing->hasMethod('shortcut') ? $existing->getMethod('shortcut')->getBody() : '// todo')
            ->addParameter('type')->setType(Shortcut::class);

        foreach ($existing->getMethods() as $method) {
            if ($method->isPrivate()) {
                $new->addMember($method);
            }
        }

        $this->writeClass($new, 'Visitor', static function (PhpNamespace $namespace): void {
            $namespace
                ->addUse(Type::class)
                ->addUse(TemplateT::class);
        });
    }

    /**
     * @param ?callable(PhpNamespace): void $processNamespace
     */
    private function writeClass(ClassType|InterfaceType|EnumType $class, ?string $namespace = null, ?callable $processNamespace = null): void
    {
        $className = $class->getName();
        \assert($className !== null);

        $fileName = \sprintf('%s%s/%s.php', $this->srcDir, $namespace === null ? '' : '/' . $namespace, $className);

        $file = (new PhpFile())
            ->setStrictTypes();

        $namespace = $file
            ->addNamespace(new PhpNamespace(self::NAMESPACE . ($namespace === null ? '' : '\\' . $namespace)))
            ->add($class);

        if ($processNamespace !== null) {
            $processNamespace($namespace);
        }

        file_put_contents($fileName, (new PsrPrinter())->printFile($file));
    }
}
