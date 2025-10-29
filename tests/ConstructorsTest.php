<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class ConstructorsTest extends TestCase
{
    private const NON_TYPE_CONSTRUCTOR_FUNCTIONS = [
        'optional',
        'param',
        'template',
        'templateOut',
        'templateIn',
        'of',
        'stringify',
        'is', // todo remove
    ];
    private const NON_TYPE_CONSTRUCTOR_CONSTANTS = [
        'Typhoon\Type\MINUS_INF',
        'Typhoon\Type\MINUS_INF_NAME',
    ];

    public function testAllSuffixedWithT(): void
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

        foreach (get_defined_constants(categorize: true)['user'] as $constant => $value) {
            if (!str_starts_with($constant, 'Typhoon\Type\\')) {
                continue;
            }

            if (\in_array($constant, self::NON_TYPE_CONSTRUCTOR_CONSTANTS, true)) {
                continue;
            }

            self::assertStringEndsWith('T', $constant);
        }
    }
}
