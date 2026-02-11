<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;
use Typhoon\Type\Visitor\Fallback;
use Typhoon\Type\Visitor\Stringify;
use Typhoon\Type\Visitor\WeakVisitor;

#[CoversClass(Stringify::class)]
#[CoversFunction('Typhoon\Type\stringify')]
final class StringifyTest extends TestCase
{
    #[DataProvider('provideCases')]
    public function test(Type $type, string $expectedString): void
    {
        $typeAsString = stringify($type);

        self::assertSame($expectedString, $typeAsString);
    }

    /**
     * @return \Generator<array-key, array{Type, string}>
     */
    public static function provideCases(): iterable
    {
        yield [neverT, 'never'];
        yield [voidT, 'void'];
        yield [mixedT, 'mixed'];
        yield [nullT, 'null'];
        yield [trueT, 'true'];
        yield [falseT, 'false'];
        yield [boolT, 'bool'];
        yield [intT, 'int'];
        yield [nonNegativeIntT, 'non-negative-int'];
        yield [nonPositiveIntT, 'non-positive-int'];
        yield [positiveIntT, 'positive-int'];
        yield [negativeIntT, 'negative-int'];
        yield [nonZeroIntT, 'non-zero-int'];
        yield [intT(123), '123'];
        yield [intT(-123), '-123'];
        yield [intRangeT(), 'int<min, max>'];
        yield [intRangeT(min: 23), 'int<23, max>'];
        yield [intRangeT(max: 23), 'int<min, 23>'];
        yield [intRangeT(min: -100, max: 234), 'int<-100, 234>'];
        yield [intMaskT(intT(1), intT(2), intT(4)), 'int-mask-of<1|2|4>'];
        yield [intMaskT(unionT(intT(1), intT(2), intT(4))), 'int-mask-of<1|2|4>']; // @phpstan-ignore argument.type, argument.type
        yield [intMaskT(constantMaskT('JSON_*')), 'int-mask-of<const<JSON_*>>'];
        yield [floatT, 'float'];
        yield [floatT(0), '0.0'];
        yield [floatT(0.0), '0.0'];
        yield [floatT(0.234), '0.234'];
        yield [floatT(-0.234), '-0.234'];
        yield [floatT(1), '1.0'];
        yield [floatT(1 / 3), '0.33333333333333'];
        yield [floatT(-1 / 3), '-0.33333333333333'];
        yield [floatT(0.012), '0.012'];
        yield [floatT(-0.012), '-0.012'];
        yield [floatT(1e3), '1000.0'];
        yield [floatT(7E-10), '0.0000000007'];
        yield [floatT(-7E-10), '-0.0000000007'];
        yield [floatT(123456E-10), '0.0000123456'];
        yield [floatT(-123456E-10), '-0.0000123456'];
        yield [floatT(1.23456789e23), '123456789000000003637248.0'];
        yield [floatT(-1.23456789e23), '-123456789000000003637248.0'];
        yield [floatT(1.23000e2), '123.0'];
        yield [floatT(1.23000e-2), '0.0123'];
        yield [floatT(PHP_FLOAT_EPSILON), '0.00000000000000022204460492503'];

        if (\PHP_VERSION_ID >= 80400) {
            yield [floatT(PHP_FLOAT_MIN), '0.000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000022250738585000'];
        }

        yield [floatT(-PHP_FLOAT_MAX), '-179769313486231570814527423731704356798070567525844996598917476803157260780028538760589558632766878171540458953514382464234321326889464182768467546703537516986049910576551282076245490090389328944075868508455133942304583236903222948165808559332123348274797826204144723168738177180919299881250404026184124858368.0'];
        yield [floatT(-PHP_FLOAT_MAX - 1), '-179769313486231570814527423731704356798070567525844996598917476803157260780028538760589558632766878171540458953514382464234321326889464182768467546703537516986049910576551282076245490090389328944075868508455133942304583236903222948165808559332123348274797826204144723168738177180919299881250404026184124858368.0'];
        yield [floatT(PHP_FLOAT_MAX), '179769313486231570814527423731704356798070567525844996598917476803157260780028538760589558632766878171540458953514382464234321326889464182768467546703537516986049910576551282076245490090389328944075868508455133942304583236903222948165808559332123348274797826204144723168738177180919299881250404026184124858368.0'];
        yield [floatT(PHP_FLOAT_MAX + 1), '179769313486231570814527423731704356798070567525844996598917476803157260780028538760589558632766878171540458953514382464234321326889464182768467546703537516986049910576551282076245490090389328944075868508455133942304583236903222948165808559332123348274797826204144723168738177180919299881250404026184124858368.0'];
        yield [floatT(NAN), 'NAN'];
        yield [floatT(INF), 'INF'];
        yield [floatT(-INF), '-INF'];
        yield [floatRangeT(), 'float<min, max>'];
        yield [floatRangeT(0, 1), 'float<0, 1>'];
        yield [floatRangeT(-0.99999, 1.232111111), 'float<-0.99999, 1.232111111>'];
        yield [floatRangeT(max: 1.3), 'float<min, 1.3>'];
        yield [floatRangeT(min: 1.3), 'float<1.3, max>'];
        yield [numericT, 'numeric'];
        yield [arrayKeyT, 'array-key'];
        yield [numericStringT, 'numeric-string'];
        yield [nonEmptyStringT, 'non-empty-string'];
        yield [truthyStringT, 'truthy-string'];
        yield [stringT, 'string'];
        yield [stringT('abcd'), "'abcd'"];
        yield [stringT("a'bcd"), "'a\\'bcd'"];
        yield [stringT("a\\\\'bcd"), "'a\\\\\\\\\\'bcd'"];
        yield [stringT("\n"), "'\\n'"];
        yield [lowercaseStringT, 'lowercase-string'];
        yield [classT(\stdClass::class), 'class-string<stdClass>'];
        yield [classT(objectT(\stdClass::class)), 'class-string<stdClass>'];
        yield [scalarT, 'scalar'];
        yield [resourceT, 'resource'];
        yield [nonEmptyListT(), 'non-empty-list<mixed>'];
        yield [nonEmptyListT(stringT), 'non-empty-list<string>'];
        yield [listT(), 'list<mixed>'];
        yield [listT(stringT), 'list<string>'];
        yield [listShapeT(), 'list{}'];
        yield [unsealedListShapeT(), 'list<mixed>'];
        yield [listShapeT([intT]), 'list{int}'];
        yield [listShapeT([intT, stringT]), 'list{int, string}'];
        yield [unsealedListShapeT([intT, 1 => stringT]), 'list{int, string, ...}'];
        yield [unsealedListShapeT([floatT], value: stringT), 'list{float, ...<string>}'];
        yield [nonEmptyArrayT(), 'non-empty-array<mixed>'];
        yield [nonEmptyArrayT(value: stringT), 'non-empty-array<string>'];
        yield [nonEmptyArrayT(stringT, intT), 'non-empty-array<string, int>'];
        yield [arrayT, 'array'];
        yield [arrayT(), 'array<mixed>'];
        yield [arrayT(unionT(intT, stringT)), 'array<mixed>'];
        yield [arrayT(unionT(stringT, intT)), 'array<mixed>'];
        yield [arrayT(nonEmptyStringT), 'array<non-empty-string, mixed>'];
        yield [arrayT(value: stringT), 'array<string>'];
        yield [arrayT(stringT, intT), 'array<string, int>'];
        yield [arrayShapeT(), 'array{}'];
        yield [unsealedArrayShapeT(), 'array<mixed>'];
        yield [arrayShapeT([intT]), 'array{0: int}'];
        yield [arrayShapeT([intT, 'a' => stringT]), "array{0: int, 'a': string}"];
        yield [unsealedArrayShapeT([intT, 'a' => stringT]), "array{0: int, 'a': string, ...}"];
        yield [arrayShapeT(['' => stringT]), "array{'': string}"];
        yield [arrayShapeT(['\'' => stringT]), "array{'\\'': string}"];
        yield [arrayShapeT(["\n" => stringT]), "array{'\\n': string}"];
        yield [unsealedArrayShapeT([intT, 'a' => stringT]), "array{0: int, 'a': string, ...}"];
        yield [arrayShapeT([optional(intT)]), 'array{0?: int}'];
        yield [unsealedArrayShapeT([optional(intT)]), 'array{0?: int, ...}'];
        yield [arrayShapeT(['a' => optional(intT)]), "array{'a'?: int}"];
        yield [unsealedArrayShapeT(['a' => floatT], key: intT, value: stringT), "array{'a': float, ...<int, string>}"];
        yield [objectT, 'object'];
        yield [objectT(\ArrayObject::class), 'ArrayObject'];
        yield [objectT(\ArrayObject::class, [arrayKeyT, stringT]), 'ArrayObject<array-key, string>'];
        yield [unionT(intT, stringT), 'int|string'];
        yield [unionT(intT, unionT(stringT, floatT)), 'int|(string|float)'];
        yield [unionT(intT, intersectionT(stringT, scalarT)), 'int|(string&scalar)'];
        yield [nullOrT(stringT), 'null|string'];
        yield [intersectionT(intT, scalarT), 'int&scalar'];
        yield [intersectionT(scalarT, unionT(stringT, floatT)), 'scalar&(string|float)'];
        yield [iterableT, 'iterable'];
        yield [iterableT(), 'iterable<mixed>'];
        yield [iterableT(value: stringT), 'iterable<string>'];
        yield [iterableT(stringT, intT), 'iterable<string, int>'];
        yield [callableT, 'callable'];
        yield [callableT(), 'callable(): mixed'];
        yield [callableT(return: callableT()), 'callable(): (callable(): mixed)'];
        yield [callableT(return: voidT), 'callable(): void'];
        yield [callableT([stringT]), 'callable(string): mixed'];
        yield [callableT([param(type: stringT, default: true)]), 'callable(string=): mixed'];
        yield [callableT([param(type: stringT, variadic: true)]), 'callable(string...): mixed'];
        yield [callableT([param(type: stringT, byRef: true)]), 'callable(string&): mixed'];
        yield [callableT([param(type: stringT, byRef: true, variadic: true)]), 'callable(string&...): mixed'];
        yield [callableT([param(type: stringT, byRef: true, variadic: true, name: 'a')]), 'callable(string &...$a): mixed'];
        yield [callableT([param(type: stringT, byRef: true, variadic: true, name: 'a')]), 'callable(string &...$a): mixed'];
        yield [closureT(), 'Closure(): mixed'];
        yield [closureT(return: closureT()), 'Closure(): (Closure(): mixed)'];
        yield [closureT(return: voidT), 'Closure(): void'];
        yield [closureT([stringT]), 'Closure(string): mixed'];
        yield [closureT([param(type: stringT, default: true)]), 'Closure(string=): mixed'];
        yield [closureT([param(type: stringT, variadic: true)]), 'Closure(string...): mixed'];
        yield [closureT([param(type: stringT, byRef: true)]), 'Closure(string&): mixed'];
        yield [closureT([param(type: stringT, byRef: true, variadic: true)]), 'Closure(string&...): mixed'];
        yield [closureT([param(type: stringT, byRef: true, variadic: true, name: 'a')]), 'Closure(string &...$a): mixed'];
        yield [objectShapeT(), 'object{}'];
        yield [objectShapeT(['name' => stringT]), 'object{name: string}'];
        yield [objectShapeT(['name' => optional(stringT)]), 'object{name?: string}'];
        yield [constantT('JSON_THROW_ON_ERROR'), 'const<JSON_THROW_ON_ERROR>'];
        yield [constantMaskT('JSON_*'), 'const<JSON_*>'];
        yield [classConstantT(\stdClass::class, 'test'), 'stdClass::test'];
        yield [classConstantMaskT(\stdClass::class, 'test_*'), 'stdClass::test_*'];
    }

    public function testItCanBeExtended(): void
    {
        $myStringify = new /** @extends Fallback<non-empty-string> */ class extends Fallback {
            private readonly Stringify $stringify;

            public function __construct()
            {
                $this->stringify = new Stringify(new WeakVisitor($this));
            }

            public function intT(IntT $type): string
            {
                return 'INT';
            }

            protected function fallback(Type $type): string
            {
                return $type->accept($this->stringify);
            }
        };

        $string = listShapeT([unionT(intT, listT(intT))])->accept($myStringify);

        self::assertSame('list{INT|list<INT>}', $string);
    }
}
