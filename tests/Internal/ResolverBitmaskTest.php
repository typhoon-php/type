<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type\Type;
use function Typhoon\Type\andT;
use function Typhoon\Type\intRangeT;
use function Typhoon\Type\intT;
use function Typhoon\Type\orT;
use function Typhoon\Type\stringify;
use const Typhoon\Type\intT;
use const Typhoon\Type\negativeIntT;
use const Typhoon\Type\nonNegativeIntT;
use const Typhoon\Type\nonPositiveIntT;
use const Typhoon\Type\nonZeroInt;
use const Typhoon\Type\positiveIntT;

#[CoversClass(ResolveBitmask::class)]
final class ResolverBitmaskTest extends TestCase
{
    #[DataProvider('provideCases')]
    public function test(Type $type, int $expected): void
    {
        $bitmask = $type->accept(new ResolveBitmask());

        self::assertSame($expected, $bitmask, \sprintf(
            'Failed asserting that %s (%s) resolves to %s',
            stringify($type),
            decbin($bitmask),
            decbin($expected),
        ));
    }

    /**
     * @return \Generator<array-key, array{Type, int}>
     */
    public static function provideCases(): iterable
    {
        yield [intT(1), 1];
        yield [intT(0), 0];
        yield [intT, -1];
        yield [negativeIntT, -1];
        yield [positiveIntT, -1];
        yield [nonNegativeIntT, -1];
        yield [nonPositiveIntT, -1];
        yield [nonZeroInt, -1];
        yield [intRangeT(1, 8), 0b1111];
        yield [orT(intT(4), intT(8)), 0b1100];
        yield [intRangeT(4, 4), 0b100];
        yield [intRangeT(4, 8), 0b1111];
        yield [andT(intT(0b111), intT(0)), 0];
        yield [andT(intT(0b111), intT(0b001)), 1];
    }
}
