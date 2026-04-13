<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(BitmaskT::class)]
final class BitmaskTTest extends TestCase
{
    public const int FLAG = 8;

    #[DataProvider('provideEvaluateCases')]
    public function testEvaluate(BitmaskT $type, int $expected): void
    {
        self::assertSame($expected, $type->evaluate());
    }

    /**
     * @return iterable<string, array{BitmaskT, int}>
     */
    public static function provideEvaluateCases(): iterable
    {
        yield 'single int' => [bitmaskT(1), 1];
        yield 'two ints' => [bitmaskT(1, 2), 1 | 2];
        yield 'three ints' => [bitmaskT(1, 2, 4), 1 | 2 | 4];
        yield 'overlapping ints' => [bitmaskT(3, 5), 3 | 5];
        yield 'intT' => [bitmaskT(intT(1), intT(4)), 1 | 4];
        yield 'constantT' => [bitmaskT(constantT('PHP_INT_SIZE')), \PHP_INT_SIZE];
        yield 'classConstantT' => [bitmaskT(classConstantT(self::class, 'FLAG')), self::FLAG];
        yield 'mixed' => [bitmaskT(1, intT(2), constantT('PHP_INT_SIZE')), 1 | 2 | \PHP_INT_SIZE];
    }

    public function testEvaluateThrowsForNonIntConstant(): void
    {
        $this->expectException(\LogicException::class);

        bitmaskT(constantT('PHP_EOL'))->evaluate();
    }

    public function testEvaluateThrowsForUnsupportedType(): void
    {
        $this->expectException(\LogicException::class);

        bitmaskT(floatT(1.5))->evaluate();
    }
}
