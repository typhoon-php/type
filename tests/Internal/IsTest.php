<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type\Type;
use function Typhoon\Type\arrayShapeT;
use function Typhoon\Type\classConstantT;
use function Typhoon\Type\constantT;
use function Typhoon\Type\intMaskT;
use function Typhoon\Type\intT;
use const Typhoon\Type\arrayT;
use const Typhoon\Type\boolT;
use const Typhoon\Type\neverT;
use const Typhoon\Type\trueT;

#[CoversFunction('Typhoon\Type\Internal\is')]
#[CoversClass(Is::class)]
#[CoversClass(ResolveBitmask::class)]
final class IsTest extends TestCase
{
    /**
     * @template T
     * @param Type<T> $type
     */
    #[DataProvider('provideCases')]
    public function test(mixed $value, Type $type, bool $expected): void
    {
        $is = is($value, $type);

        self::assertSame($expected, $is);
    }

    /**
     * @return \Generator<array-key, array{mixed, Type, bool}>
     */
    public static function provideCases(): iterable
    {
        yield [true, neverT, false];
        yield [true, trueT, true];
        yield [true, boolT, true];
        yield [1, boolT, false];
        yield [1, intMaskT(1), true];
        yield [8, intMaskT(1, 2, 4), false];
        yield [PHP_INT_MAX, constantT('PHP_INT_MAX'), true];
        yield [\DateTimeInterface::ATOM, classConstantT(\DateTimeInterface::class, 'ATOM'), true];
        yield [[], arrayT, true];
        yield [['a' => 1], arrayT, true];
        yield [['a' => 1], arrayShapeT(['a' => intT(1)]), true];
        yield [['a' => 1, 'b' => 2], arrayShapeT(['a' => intT(1)]), false];
    }
}
