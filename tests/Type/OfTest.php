<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;

#[CoversFunction('Typhoon\Type\of')]
final class OfTest extends TestCase
{
    #[DataProvider('provideCases')]
    public function test(mixed $value, Type $expectedType): void
    {
        $type = of($value);

        self::assertEquals($expectedType, $type);
    }

    /**
     * @return \Generator<array-key, array{mixed, Type}>
     */
    public static function provideCases(): iterable
    {
        yield [null, nullT];
        yield [false, falseT];
        yield [true, trueT];
        yield [true, trueT];
        yield [1, intT(1)];
        yield [-1, intT(-1)];
        yield [0.5, floatT(0.5)];
        yield [[], listShapeT()];
        yield [[1, 2, 'a'], listShapeT([intT(1), intT(2), stringT('a')])];
        yield [['a' => 'b'], arrayShapeT(['a' => stringT('b')])];
        yield [['a' => ['b']], arrayShapeT(['a' => listShapeT([stringT('b')])])];
        yield [new \stdClass(), namedObjectT(\stdClass::class)];
        yield [STDIN, resourceT];
    }
}
