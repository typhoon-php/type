<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConstantT::class)]
final class ConstantTTest extends TestCase
{
    #[DataProvider('provideEvaluateCases')]
    public function testEvaluate(ConstantT $type, mixed $expected): void
    {
        self::assertSame($expected, $type->evaluate());
    }

    /**
     * @return iterable<string, array{ConstantT, mixed}>
     */
    public static function provideEvaluateCases(): iterable
    {
        yield 'int constant' => [constantT('PHP_INT_SIZE'), \PHP_INT_SIZE];
        yield 'string constant' => [constantT('PHP_EOL'), PHP_EOL];
        yield 'bool constant' => [constantT('PHP_DEBUG'), PHP_DEBUG];
    }

    public function testEvaluateThrowsForUndefinedConstant(): void
    {
        $this->expectException(\Error::class);

        constantT('TYPHOON_UNDEFINED_CONSTANT')->evaluate();
    }
}
