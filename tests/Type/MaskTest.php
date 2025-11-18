<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(Mask::class)]
final class MaskTest extends TestCase
{
    /**
     * @param non-empty-string $mask
     */
    #[TestWith(['_', '_'])]
    #[TestWith(['_', '_X', false])]
    #[TestWith(['*', ''])]
    #[TestWith(['*', 'ABC'])]
    #[TestWith(['*', 'ABC_*_DEF'])]
    #[TestWith(['*_', 'ABC_'])]
    #[TestWith(['*_', 'ABC', false])]
    #[TestWith(['*/', 'ABC/'])]
    #[TestWith(['*(', 'ABC('])]
    #[TestWith(['*_*', 'ABC_DEF'])]
    #[TestWith(['*_*', 'ABC_'])]
    #[TestWith(['*_*', '_'])]
    #[TestWith(['*_*', '', false])]
    public function testMatches(string $mask, string $string, bool $expected = true): void
    {
        $mask  = new Mask($mask);

        $matches = $mask->test($string);

        self::assertSame($expected, $matches);
    }
}
