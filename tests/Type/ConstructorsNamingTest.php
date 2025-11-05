<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class ConstructorsNamingTest extends TestCase
{
    private const NON_TYPE_CONSTRUCTOR_FUNCTIONS = [
        'optional',
        'param',
        'stringify',
        'template',
        'templateIn',
        'templateOut',
    ];

    public function testFunctionsSuffixedWithT(): void
    {
        foreach (get_defined_functions()['user'] as $function) {
            if (!str_starts_with($function, 'typhoon\type\\')) {
                continue;
            }

            $shortName = (new \ReflectionFunction($function))->getShortName();

            if (\in_array($shortName, self::NON_TYPE_CONSTRUCTOR_FUNCTIONS, true)) {
                continue;
            }

            self::assertStringEndsWith('T', $shortName);
        }
    }

    public function testConstantsSuffixedWithT(): void
    {
        foreach (get_defined_constants(categorize: true)['user'] as $constant => $value) {
            if (str_starts_with($constant, 'Typhoon\Type\\')) {
                self::assertStringEndsWith('T', $constant);
            }
        }
    }
}
