<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;

#[CoversNothing]
final class ConstructorsNamingTest extends TestCase
{
    public function testFunctionsAreSuffixedWithT(): void
    {
        foreach (get_defined_functions()['user'] as $function) {
            if (!str_starts_with($function, 'typhoon\type\\')) {
                continue;
            }

            $reflection = new \ReflectionFunction($function);
            $returnType = $reflection->getReturnType();

            if (!$returnType instanceof \ReflectionNamedType) {
                continue;
            }

            if (!is_a($returnType->getName(), Type::class, allow_string: true)) {
                continue;
            }

            self::assertStringEndsWith('T', $reflection->getShortName());
        }
    }

    public function testConstantsAreSuffixedWithT(): void
    {
        foreach (get_defined_constants(categorize: true)['user'] as $constant => $value) {
            if ($value instanceof Type) {
                self::assertStringEndsWith('T', $constant);
            }
        }
    }
}
