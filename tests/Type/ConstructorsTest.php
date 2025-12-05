<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;
use Typhoon\Type\Internal\Optional;

#[CoversFunction('Typhoon\Type\intT')]
#[CoversFunction('Typhoon\Type\intRangeT')]
#[CoversFunction('Typhoon\Type\bitmaskT')]
#[CoversFunction('Typhoon\Type\intMaskT')]
#[CoversFunction('Typhoon\Type\floatT')]
#[CoversFunction('Typhoon\Type\floatRangeT')]
#[CoversFunction('Typhoon\Type\stringT')]
#[CoversFunction('Typhoon\Type\classT')]
#[CoversFunction('Typhoon\Type\optional')]
#[CoversFunction('Typhoon\Type\listT')]
#[CoversFunction('Typhoon\Type\nonEmptyListT')]
#[CoversFunction('Typhoon\Type\listShapeT')]
#[CoversFunction('Typhoon\Type\unsealedListShapeT')]
#[CoversFunction('Typhoon\Type\arrayT')]
#[CoversFunction('Typhoon\Type\nonEmptyArrayT')]
#[CoversFunction('Typhoon\Type\arrayShapeT')]
#[CoversFunction('Typhoon\Type\unsealedArrayShapeT')]
#[CoversFunction('Typhoon\Type\iterableT')]
#[CoversFunction('Typhoon\Type\objectT')]
#[CoversFunction('Typhoon\Type\objectShapeT')]
#[CoversFunction('Typhoon\Type\callableT')]
#[CoversFunction('Typhoon\Type\closureT')]
#[CoversFunction('Typhoon\Type\param')]
#[CoversFunction('Typhoon\Type\constantT')]
#[CoversFunction('Typhoon\Type\constantMaskT')]
#[CoversFunction('Typhoon\Type\classConstantT')]
#[CoversFunction('Typhoon\Type\classConstantMaskT')]
#[CoversFunction('Typhoon\Type\intersectionT')]
#[CoversFunction('Typhoon\Type\unionT')]
#[CoversFunction('Typhoon\Type\nullOrT')]
#[CoversClass(ArrayElement::class)]
#[CoversClass(Property::class)]
#[CoversClass(Optional::class)]
#[CoversClass(Parameter::class)]
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
        foreach (get_defined_constants(categorize: true)['user'] ?? [] as $constant => $value) {
            if (str_starts_with($constant, 'Typhoon\Type\\')) {
                self::assertInstanceOf(Type::class, $value);
                self::assertStringEndsWith('T', $constant);
            }
        }
    }

    #[DoesNotPerformAssertions]
    public function testConstructorsCoverage(): void
    {
        iterator_to_array(StringifyTest::provideCases(), preserve_keys: false);
    }
}
