<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassConstantT::class)]
final class ClassConstantTTest extends TestCase
{
    public const int FLAG = 8;

    #[DataProvider('provideEvaluateCases')]
    public function testEvaluate(ClassConstantT $type, mixed $expected): void
    {
        self::assertSame($expected, $type->evaluate());
    }

    /**
     * @return iterable<string, array{ClassConstantT, mixed}>
     */
    public static function provideEvaluateCases(): iterable
    {
        yield 'int constant' => [classConstantT(self::class, 'FLAG'), self::FLAG];
        yield 'string constant' => [classConstantT(\DateTimeInterface::class, 'ATOM'), \DateTimeInterface::ATOM];
        yield 'built-in int constant' => [classConstantT(\SplFileObject::class, 'READ_CSV'), \SplFileObject::READ_CSV];
    }

    public function testEvaluateThrowsForUndefinedClassConstant(): void
    {
        $this->expectException(\Error::class);

        /** @phpstan-ignore method.resultUnused */
        classConstantT(self::class, 'UNDEFINED')->evaluate();
    }

    public function testEvaluateThrowsForUndefinedClass(): void
    {
        $this->expectException(\Error::class);

        /** @phpstan-ignore argument.type, method.resultUnused */
        classConstantT('NonExistentClass', 'FLAG')->evaluate();
    }
}
