<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type\Type;
use function Typhoon\Type\arrayShapeT;
use function Typhoon\Type\classConstantMaskT;
use function Typhoon\Type\classConstantT;
use function Typhoon\Type\constantT;
use function Typhoon\Type\floatT;
use function Typhoon\Type\intMaskT;
use function Typhoon\Type\intRangeT;
use function Typhoon\Type\intT;
use function Typhoon\Type\listShapeT;
use function Typhoon\Type\listT;
use function Typhoon\Type\nonEmptyArrayT;
use function Typhoon\Type\nonEmptyListT;
use function Typhoon\Type\unsealedListShapeT;
use const Typhoon\Type\arrayT;
use const Typhoon\Type\boolT;
use const Typhoon\Type\intT;
use const Typhoon\Type\neverT;
use const Typhoon\Type\stringT;
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
        yield [1, intT, true];
        // yield ['a', intRangeT(1, 10), false];
        // yield [1, intRangeT(1, 10), true];
        // yield [5, intRangeT(1, 10), true];
        // yield [10, intRangeT(1, 10), true];
        // yield [-1, intRangeT(1, 10), false];
        // yield [11, intRangeT(1, 10), false];
        yield [1, intMaskT(1), true];
        yield [8, intMaskT(1, 2, 4), false];
        yield [PHP_INT_MAX, constantT('PHP_INT_MAX'), true];
        // yield [INF, floatT(INF), true];
        // yield [-INF, floatT(-INF), true];
        // yield [NAN, floatT(NAN), true];
        yield [\DateTimeInterface::ATOM, classConstantT(\DateTimeInterface::class, 'ATOM'), true];
        yield [[], listT(), true];
        yield [[], nonEmptyListT(), false];
        yield [[1], listT(intT), true];
        yield [[1], listT(stringT), false];
        yield [[1, 2], listShapeT([intT]), false];
        yield [[1, 'a'], listShapeT([intT, stringT]), true];
        yield [[1, 'a'], unsealedListShapeT([intT]), true];
        yield [[1, 'a'], unsealedListShapeT([intT], intT), false];
        yield [['a' => 1], listT(), false];
        yield [[], arrayT, true];
        yield [[], nonEmptyArrayT(), false];
        yield [['a' => 1], arrayT, true];
        yield [['a' => 1], arrayShapeT(['a' => intT(1)]), true];
        yield [['a' => 1, 'b' => 2], arrayShapeT(['a' => intT(1)]), false];
        yield [\ReflectionClass::IS_EXPLICIT_ABSTRACT, classConstantMaskT(\ReflectionClass::class, 'IS_*'), true];
        yield ['a', classConstantMaskT(\ReflectionClass::class, 'IS_*'), false];
    }
}
