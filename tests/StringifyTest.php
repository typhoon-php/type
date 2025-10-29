<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Typhoon\Type\Visitor\Stringify;

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
        yield [intRangeT(), 'int'];
        yield [intRangeT(min: 23), 'int<23, max>'];
        yield [intRangeT(max: 23), 'int<min, 23>'];
        yield [intRangeT(min: -100, max: 234), 'int<-100, 234>'];
        yield [intMaskT(intT(1), intT(2), intT(4)), 'int-mask-of<1|2|4>'];
        yield [intMaskT(unionT(intT(1), intT(2), intT(4))), 'int-mask-of<1|2|4>'];
        yield [intMaskT(classConstantT(\RecursiveIteratorIterator::class, 'LEAVES_ONLY')), 'int-mask-of<RecursiveIteratorIterator::LEAVES_ONLY>'];
        yield [floatT, 'float'];
        yield [floatT('0.234'), '0.234'];
        yield [floatT(0.234), '0.234'];
        yield [floatT(-0.234), '-0.234'];
        yield [floatRangeT(), 'float'];
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
        yield [literalStringT, 'literal-string'];
        yield [lowercaseStringT, 'lowercase-string'];
        yield [classT(\stdClass::class), 'class-string<stdClass>'];
        yield [scalarT, 'scalar'];
        yield [resourceT, 'resource'];
        yield [nonEmptyListT(), 'non-empty-list'];
        yield [nonEmptyListT(stringT), 'non-empty-list<string>'];
        yield [listT(), 'list'];
        yield [listT(stringT), 'list<string>'];
        yield [listShapeT(), 'list{}'];
        yield [unsealedListShapeT(), 'list'];
        yield [listShapeT([intT]), 'list{int}'];
        yield [listShapeT([intT, stringT]), 'list{int, string}'];
        yield [unsealedListShapeT([intT, 1 => stringT]), 'list{int, string, ...}'];
        yield [unsealedListShapeT([floatT], value: stringT), 'list{float, ...<string>}'];
        yield [nonEmptyArrayT(), 'non-empty-array'];
        yield [nonEmptyArrayT(value: stringT), 'non-empty-array<string>'];
        yield [nonEmptyArrayT(stringT, intT), 'non-empty-array<string, int>'];
        yield [arrayT, 'array'];
        yield [arrayT(), 'array'];
        yield [arrayT(orT(intT, stringT)), 'array'];
        yield [arrayT(orT(stringT, intT)), 'array'];
        yield [arrayT(nonEmptyStringT), 'array<non-empty-string, mixed>'];
        yield [arrayT(value: stringT), 'array<string>'];
        yield [arrayT(stringT, intT), 'array<string, int>'];
        yield [arrayShapeT(), 'array{}'];
        yield [unsealedArrayShapeT(), 'array'];
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
        yield [namedObjectT(\ArrayObject::class), 'ArrayObject'];
        yield [namedObjectT(\ArrayObject::class, [arrayKeyT, stringT]), 'ArrayObject<array-key, string>'];
        yield [selfT, 'self'];
        yield [selfT([stringT]), 'self<string>'];
        yield [parentT, 'parent'];
        yield [parentT([stringT]), 'parent<string>'];
        yield [staticT, 'static'];
        yield [staticT([stringT]), 'static<string>'];
        yield [unionT(intT, stringT), 'int|string'];
        yield [unionT(intT, unionT(stringT, floatT)), 'int|(string|float)'];
        yield [unionT(intT, intersectionT(stringT, floatT)), 'int|string&float'];
        yield [intersectionT(intT, stringT), 'int&string'];
        yield [intersectionT(intT, intersectionT(stringT, floatT)), 'int&string&float'];
        yield [intersectionT(intT, unionT(stringT, floatT)), 'int&(string|float)'];
        yield [iterableT, 'iterable'];
        yield [iterableT(), 'iterable'];
        yield [iterableT(value: stringT), 'iterable<string>'];
        yield [iterableT(stringT, intT), 'iterable<string, int>'];
        yield [callableT, 'callable'];
        yield [callableT(), 'callable'];
        yield [callableT([template('T')]), 'callable<T>(): mixed'];
        yield [callableT(return: callableT()), 'callable(): callable'];
        yield [callableT(return: voidT), 'callable(): void'];
        yield [callableT(params: [stringT]), 'callable(string): mixed'];
        yield [callableT(params: [param(type: stringT, default: true)]), 'callable(string=): mixed'];
        yield [callableT(params: [param(type: stringT, default: numericStringT)]), 'callable(string=numeric-string): mixed'];
        yield [callableT(params: [param(type: stringT, variadic: true)]), 'callable(string...): mixed'];
        yield [callableT(params: [param(type: stringT, byRef: true)]), 'callable(string&): mixed'];
        yield [callableT(params: [param(type: stringT, byRef: true, variadic: true)]), 'callable(string&...): mixed'];
        yield [callableT(params: [param('a', stringT, byRef: true, variadic: true)]), 'callable(string &...$a): mixed'];
        yield [closureT(), 'Closure'];
        yield [closureT([template('T')]), 'Closure<T>(): mixed'];
        yield [closureT(return: closureT()), 'Closure(): Closure'];
        yield [closureT(return: voidT), 'Closure(): void'];
        yield [closureT(params: [stringT]), 'Closure(string): mixed'];
        yield [closureT(params: [param(type: stringT, default: true)]), 'Closure(string=): mixed'];
        yield [closureT(params: [param(type: stringT, default: numericStringT)]), 'Closure(string=numeric-string): mixed'];
        yield [closureT(params: [param(type: stringT, variadic: true)]), 'Closure(string...): mixed'];
        yield [closureT(params: [param(type: stringT, byRef: true)]), 'Closure(string&): mixed'];
        yield [closureT(params: [param(type: stringT, byRef: true, variadic: true)]), 'Closure(string&...): mixed'];
        yield [closureT(params: [param('a', stringT, byRef: true, variadic: true)]), 'Closure(string &...$a): mixed'];
        yield [objectShapeT(), 'object'];
        yield [objectShapeT(['name' => stringT]), 'object{name: string}'];
        yield [objectShapeT(['name' => optional(stringT)]), 'object{name?: string}'];
        yield [constantT('JSON_THROW_ON_ERROR'), 'const<JSON_THROW_ON_ERROR>'];
        yield [constantMaskT('JSON_*'), 'const<JSON_*>'];
        yield [classConstantT(\stdClass::class, 'test'), 'stdClass::test'];
        yield [classConstantMaskT(\stdClass::class, 'test_*'), 'stdClass::test_*'];
        yield [keyT(arrayT), 'key-of<array>'];
        yield [valueT(arrayT), 'value-of<array>'];
        yield [offsetT(nonEmptyListT(), intT(0)), 'non-empty-list[0]'];
        yield [ternaryT(trueT, then: intT, else: floatT), 'true ? int : float'];
        yield [isSubtypeT(trueT, mixedT), 'true is mixed'];
        yield [isSupertypeT(boolT, falseT), 'false is bool'];
        yield [aliasT(\stdClass::class, 'A'), 'stdClass@A'];
        $T = template('T');
        yield [listShapeT([$T->type, template('T')->type, $T->type]), 'list{T#0, T#1, T#0}'];
        yield 'unknown generic name is preserved' => [listShapeT([$T->type, callableT([$T])]), 'list{T#0, callable<T#0>(): mixed}'];
        $TB = template('T', scalarT, stringT);
        yield [callableT([$TB], [$TB->type], $TB->type), 'callable<T of scalar super string>(T): T'];
        yield [objectT([$T], [namedObjectT(\stdClass::class, [intT])], ['p' => $T->type]), 'object<T>:stdClass<int>{p: T}'];
        yield [objectT([templateIn('I', default: intT), templateOut('O')]), 'object<in I = int, out O>'];
    }
}
