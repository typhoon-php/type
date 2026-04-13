<?php

declare(strict_types=1);

namespace Typhoon\Type\Visitor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;

#[CoversClass(Reduced::class)]
final class ReducedTest extends TestCase
{
    public const int CONST_1 = 1;
    public const int CONST_2 = 2;
    protected const int CONST_PROTECTED = 3;

    /** @phpstan-ignore classConstant.unused */
    private const int CONST_PRIVATE = 3;

    #[DataProvider('provideCases')]
    public static function test(Type $type, Type $reduced): void
    {
        $actual = $type->accept(new Reduce());

        self::assertEquals($reduced, $actual);
    }

    /**
     * @return \Generator<array-key, array{Type, Type}>
     */
    public static function provideCases(): iterable
    {
        yield 'non-matching const mask' => [Type\constantMaskT('#'), Type\neverT];
        yield '1 matching const mask' => [Type\constantMaskT('PHP_EOL'), Type\constantT('PHP_EOL')];
        yield 'PHP_INT_*' => [Type\constantMaskT('PHP_INT_*'), Type\unionT(
            Type\constantT('PHP_INT_MAX'),
            Type\constantT('PHP_INT_MIN'),
            Type\constantT('PHP_INT_SIZE'),
        )];

        yield 'non-matching class const mask' => [Type\classConstantMaskT(self::class, '#'), Type\neverT];
        yield '1 matching class const mask' => [Type\classConstantMaskT(self::class, 'CONST_1'), Type\classConstantT(self::class, 'CONST_1')];
        yield 'multiple matching class const mask' => [Type\classConstantMaskT(self::class, 'CONST_*'), Type\unionT(
            Type\classConstantT(self::class, 'CONST_1'),
            Type\classConstantT(self::class, 'CONST_2'),
        )];
    }
}
