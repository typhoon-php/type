<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;

#[CoversNothing]
final class ConstructorsTest extends TestCase
{
    private const NON_TYPE_CONSTRUCTOR_FUNCTIONS = [
        'optional',
        'param',
        'stringify',
    ];

    public function testFunctionsAreSuffixedWithT(): void
    {
        foreach (get_defined_functions()['user'] as $function) {
            if (!str_starts_with($function, 'typhoon\type\\')) {
                continue;
            }

            $reflection = new \ReflectionFunction($function);
            $shortName = $reflection->getShortName();
            $returnType = $reflection->getReturnType();

            if (\in_array($shortName, self::NON_TYPE_CONSTRUCTOR_FUNCTIONS, strict: true)) {
                continue;
            }

            self::assertStringEndsWith('T', $shortName);
            self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
            self::assertTrue(is_a($returnType->getName(), Type::class, allow_string: true), $shortName);
            self::assertNotSame(Type::class, $returnType->getName());
        }
    }

    public function testConstantsAreSuffixedWithT(): void
    {
        foreach (get_defined_constants(categorize: true)['user'] as $constant => $value) {
            if (str_starts_with($constant, 'Typhoon\Type\\')) {
                self::assertInstanceOf(Type::class, $value);
                self::assertStringEndsWith('T', $constant);
            }
        }
    }
}
