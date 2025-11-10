<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\TraitType;
use Symfony\Component\Finder\Finder;
use Typhoon\Type as TypeI;
use Typhoon\Type\Generator\Spec\Type;
use Typhoon\Type\Visitor;

final readonly class Generator
{
    private const GENERATED_NOTICE = '@generated This file was generated, do not edit manually.';
    private const NAMESPACE = 'Typhoon\Type';

    /**
     * @param non-empty-string $typeDir
     * @param non-empty-list<Type> $types
     */
    public function __construct(
        private string $typeDir,
        private array $types,
    ) {}

    public function cleanUp(): void
    {
        $files = Finder::create()
            ->in($this->typeDir)
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
        $visitor = (new TraitType('Reduced'))
            ->addComment('@api')
            ->addComment('@codeCoverageIgnore');

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
            $visitor->addMember($type->fallbackMethod());
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

    private function write(PhpNamespace $namespace): void
    {
        $file = (new PhpFile())
            ->setStrictTypes()
            ->setComment(self::GENERATED_NOTICE);

        $file->addNamespace($namespace);

        $code = preg_replace("/'%(.*?)%'/s", '$1', (new PsrPrinter())->printFile($file));

        file_put_contents($this->resolveFileName($namespace), $code);
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
        $class = array_first($namespace->getClasses());
        \assert($class instanceof ClassLike);

        $className = $class->getName();
        \assert($className !== null);

        $subDir = str_replace('\\', '/', substr($namespace->getName(), \strlen(self::NAMESPACE)));

        $fileName = \sprintf('%s%s/%s.php', $this->typeDir, $subDir, $className);

        $dir = \dirname($fileName);

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
        }

        return $fileName;
    }
}
