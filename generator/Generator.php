<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Finder\Finder;
use Typhoon\Type\Type;
use Typhoon\Type\TypeVisitor;

const GENERATING = true;

final class Generator
{
    private const GENERATED_NOTICE = "This code is generated, do not edit it.\n";
    private const NAMESPACE = 'Typhoon\Type';
    private const SRC = __DIR__ . '/../src';

    public static function generate(): void
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        self::cleanUp();
        self::generateTypes();
        self::generateVisitor();
        self::generateDefaultVisitor();
    }

    private static function cleanUp(): void
    {
        $files = Finder::create()
            ->in(self::SRC)
            ->name('*.php')
            ->contains(self::GENERATED_NOTICE);

        foreach ($files as $file) {
            unlink($file->getPathname());
        }
    }

    private static function generateTypes(): void
    {
        foreach (self::types() as $type) {
            if ($type->properties === []) {
                self::writeClass(self::generateAtomicType($type));
            } else {
                self::writeClass(self::generateComplexType($type));
            }
        }
    }

    private static function generateVisitor(): void
    {
        $visitor = (new InterfaceType('TypeVisitor'))
            ->setComment(self::GENERATED_NOTICE . "\n@api\n@template-covariant TResult");

        foreach (self::types() as $type) {
            $visitor
                ->addMethod($type->name)
                ->setPublic()
                ->setReturnType('mixed')
                ->setComment('@return TResult')
                ->addParameter('type')->setType($type->className());
        }

        self::writeClass($visitor);
    }

    private static function generateDefaultVisitor(): void
    {
        $visitor = (new ClassType('DefaultTypeVisitor'))
            ->setAbstract()
            ->addImplement(TypeVisitor::class)
            ->setComment(self::GENERATED_NOTICE . "\n@api\n@template-covariant TResult\n@implements TypeVisitor<TResult>");

        foreach (self::types() as $type) {
            $visitor
                ->addMethod($type->name)
                ->setPublic()
                ->setReturnType('mixed')
                ->setBody('return $this->default($type);')
                ->addParameter('type')->setType($type->className());
        }

        $visitor
            ->addMethod('default')
            ->setPublic()
            ->setAbstract()
            ->setReturnType('mixed')
            ->setComment('@return TResult')
            ->addParameter('type')->setType(Type::class);

        self::writeClass($visitor, 'Visitor');
    }

    private static function generateAtomicType(TypeSpec $type): EnumType
    {
        $enum = (new EnumType($type->shortClassName()))
            ->setComment(self::GENERATED_NOTICE . "\n@api\n@implements Type<{$type->type}>")
            ->addImplement(Type::class);
        $enum->addCase('T');
        $enum->addMethod('accept')
            ->setReturnType('mixed')
            ->addBody("return \$visitor->{$type->name}(\$this);")
            ->addParameter('visitor')->setType(TypeVisitor::class);

        return $enum;
    }

    private static function generateComplexType(TypeSpec $typeSpec): ClassType
    {
        $typeComment = self::GENERATED_NOTICE . "\n@api";

        foreach ($typeSpec->templates as $templateSpec) {
            $typeComment .= \sprintf(
                "\n@template %s%s%s",
                $templateSpec->name,
                $templateSpec->of === null ? '' : ' of ' . $templateSpec->of,
                $templateSpec->default === null ? '' : ' = ' . $templateSpec->default,
            );
        }

        $typeComment .= "\n@implements Type<{$typeSpec->type}>";

        $class = (new ClassType($typeSpec->shortClassName()))
            ->setFinal()
            ->setComment($typeComment)
            ->addImplement(Type::class);

        $constructorComment = "@internal\n@psalm-internal Typhoon\\Type";
        $constructorParams = [];
        $constructorBody = '';

        foreach ($typeSpec->properties as $propertySpec) {
            $nativeType = $propertySpec->nativeType();
            $class->addProperty($propertySpec->name)
                ->setReadOnly()
                ->setComment('@var ' . $propertySpec->type)
                ->setType($nativeType);
            $constructorComment .= \sprintf("\n@param %s $%s", $propertySpec->type, $propertySpec->name);
            $constructorParams[] = (new Parameter($propertySpec->name))->setType($nativeType);
            $constructorBody .= \sprintf('$this->%s = $%1$s;' . PHP_EOL, $propertySpec->name);
        }

        $class
            ->addMethod('__construct')
            ->setComment($constructorComment)
            ->setParameters($constructorParams)
            ->setBody($constructorBody);

        $class
            ->addMethod('accept')
            ->setReturnType('mixed')
            ->addBody(\sprintf('return $visitor->%s($this);', $typeSpec->name))
            ->addParameter('visitor')->setType(TypeVisitor::class);

        return $class;
    }

    /**
     * @return non-empty-list<TypeSpec>
     */
    private static function types(): array
    {
        /** @var non-empty-list<TypeSpec> */
        return require __DIR__ . '/types.php';
    }

    private static function writeClass(ClassType|InterfaceType|EnumType $class, ?string $namespace = null): void
    {
        $className = $class->getName();
        \assert($className !== null);

        $fileName = \sprintf('%s%s/%s.php', self::SRC, $namespace === null ? '' : '/' . $namespace, $className);

        $file = new PhpFile();
        $file->setStrictTypes();

        $namespace = $file->addNamespace(new PhpNamespace(self::NAMESPACE . ($namespace === null ? '' : '\\' . $namespace)));
        $namespace->add($class);
        $namespace->addUse(Type::class);

        file_put_contents($fileName, (new PsrPrinter())->printFile($file));
    }

    private function __construct() {}
}
