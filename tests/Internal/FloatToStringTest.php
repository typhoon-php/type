<?php

declare(strict_types=1);

namespace Typhoon\Type\Internal;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Type\Internal\floatToString')]
final class FloatToStringTest extends TestCase
{
    #[TestWith([0, '0'])]
    #[TestWith([-0, '0'])]
    #[TestWith([0.0, '0'])]
    #[TestWith([-0.0, '-0'])]
    #[TestWith([0.012, '0.012'])]
    #[TestWith([-0.012, '-0.012'])]
    #[TestWith([7E-10, '0.0000000007'])]
    #[TestWith([-7E-10, '-0.0000000007'])]
    #[TestWith([123456E-10, '0.0000123456'])]
    #[TestWith([-123456E-10, '-0.0000123456'])]
    #[TestWith([1.23456789e23, '123456789000000003637248'])]
    #[TestWith([-1.23456789e23, '-123456789000000003637248'])]
    public function test(float $value, string $expected): void
    {
        $string = floatToString($value);

        /** @phpstan-ignore staticMethod.alreadyNarrowedType */
        self::assertIsNumeric($string);
        self::assertSame($expected, $string);
        self::assertSame($value, (float) $string);
    }
}
