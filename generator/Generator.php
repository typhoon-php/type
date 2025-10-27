<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Component\Finder\Finder;
use Typhoon\Type\Generator\Spec\Type;
use Typhoon\Type\Type as TypeI;
use Typhoon\Type\Visitor;

final readonly class Generator
{
    private const GENERATED_NOTICE = '@generated This file was generated, do not edit manually.';
    private const NAMESPACE = 'Typhoon\Type';

    /**
     * @param non-empty-string $srcDir
     * @param non-empty-list<Type> $types
     */
    public function __construct(
        private string $srcDir,
        private array $types,
    ) {}

    public function cleanUp(): void
    {
        $files = Finder::create()
            ->in($this->srcDir)
            ->name('*.php')
            ->contains(self::GENERATED_NOTICE);

        foreach ($files as $file) {
            unlink($file->getPathname());
        }
    }

    public function generateTypes(): void
    {
        foreach ($this->types as $type) {
            $this->write($this->createNamespace()->add($type->class()));
        }
    }

    public function generateVisitor(): void
    {
        $visitor = (new InterfaceType('Visitor'))
            ->setComment(
                <<<'PHPDOC'
                    @api
                    @template-covariant TResult
                    PHPDOC,
            );

        foreach ($this->types as $type) {
            $visitor->addMember($type->visitorMethod());
        }

        $this->write($this->createNamespace()->add($visitor));
    }

    public function generateReduced(): void
    {
        $visitor = (new ClassType('Reduced'))
            ->addImplement(Visitor::class)
            ->setAbstract()
            ->setComment(
                <<<'PHPDOC'
                    @api
                    @template-covariant TResult
                    @implements Visitor<TResult>
                    @codeCoverageIgnore
                    PHPDOC,
            );

        foreach ($this->types as $type) {
            if ($type->isReducible()) {
                $visitor->addMember($type->reducedMethod($this->types));
            }
        }

        $this->write($this->createNamespace('Visitor')->add($visitor));
    }

    public function generateFallback(): void
    {
        $visitor = (new ClassType('Fallback'))
            ->setExtends(Visitor::class . '\Reduced')
            ->setAbstract()
            ->setComment(
                <<<'PHPDOC'
                    @api
                    @template-covariant TResult
                    @extends Reduced<TResult>
                    @codeCoverageIgnore
                    PHPDOC,
            );

        foreach ($this->types as $type) {
            if (!$type->isReducible()) {
                $visitor->addMember($type->fallbackMethod());
            }
        }

        $visitor->addMethod('fallback')
            ->setAbstract()
            ->setProtected()
            ->setReturnType('mixed')
            ->setComment('@return TResult')
            ->setParameters([
                (new Parameter('type'))->setType(TypeI::class),
            ]);

        $this->write($this->createNamespace('Visitor')->add($visitor));
    }

    public function generateStringify(): void
    {
        $code = file_get_contents(__DIR__ . '/Visitor/Stringify.php');
        \assert($code !== false);

        $file = PhpFile::fromCode($code);

        $namespace = array_first($file->getNamespaces());
        \assert($namespace instanceof PhpNamespace);

        (new \ReflectionClass($namespace))->getProperty('name')->setValue($namespace, self::NAMESPACE . '\Visitor');

        $visitor = array_first($namespace->getClasses());
        \assert($visitor instanceof ClassType);

        foreach ($this->types as $type) {
            if (!$visitor->hasMethod($type->name)) {
                $visitor->addMember($type->stringifyMethod());
            }
        }

        $orderMap = array_combine(array_column($this->types, 'name'), array_keys($this->types));
        $methods = $visitor->getMethods();

        usort($methods, static fn(Method $a, Method $b): int => ($orderMap[$a->getName()] ?? -1) <=> ($orderMap[$b->getName()] ?? -1));

        $visitor->setMethods($methods);

        $this->write($namespace);
    }

    private function write(PhpNamespace $namespace): void
    {
        $file = (new PhpFile())
            ->setStrictTypes()
            ->setComment(self::GENERATED_NOTICE);

        $file->addNamespace($namespace);

        file_put_contents($this->resolveFileName($namespace), (new PsrPrinter())->printFile($file));
    }

    /**
     * @param ?non-empty-string $sub
     */
    private function createNamespace(?string $sub = null): PhpNamespace
    {
        return new PhpNamespace(self::NAMESPACE . ($sub === null ? '' : '\\' . $sub));
    }

    private function resolveFileName(PhpNamespace $namespace): string
    {
        $namespace->addUse(\Closure::class);

        $class = array_first($namespace->getClasses());
        \assert($class instanceof ClassLike);

        $className = $class->getName();
        \assert($className !== null);

        $subDir = str_replace('\\', '/', substr($namespace->getName(), \strlen(self::NAMESPACE)));

        $fileName = \sprintf('%s%s/%s.php', $this->srcDir, $subDir, $className);

        $dir = \dirname($fileName);

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
        }

        return $fileName;
    }
}
