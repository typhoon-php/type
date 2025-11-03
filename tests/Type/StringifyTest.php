<?php

declare(strict_types=1);

namespace Typhoon\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Typhoon\Type;
use Typhoon\Type\Visitor\Fallback;
use Typhoon\Type\Visitor\Stringify;

#[CoversClass(Stringify::class)]
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
#[CoversFunction('Typhoon\Type\keyT')]
#[CoversFunction('Typhoon\Type\valueT')]
#[CoversFunction('Typhoon\Type\offsetT')]
#[CoversFunction('Typhoon\Type\iterableT')]
#[CoversFunction('Typhoon\Type\objectT')]
#[CoversFunction('Typhoon\Type\objectShapeT')]
#[CoversFunction('Typhoon\Type\namedObjectT')]
#[CoversFunction('Typhoon\Type\selfT')]
#[CoversFunction('Typhoon\Type\parentT')]
#[CoversFunction('Typhoon\Type\staticT')]
#[CoversFunction('Typhoon\Type\callableT')]
#[CoversFunction('Typhoon\Type\closureT')]
#[CoversFunction('Typhoon\Type\param')]
#[CoversFunction('Typhoon\Type\constantT')]
#[CoversFunction('Typhoon\Type\constantMaskT')]
#[CoversFunction('Typhoon\Type\classConstantT')]
#[CoversFunction('Typhoon\Type\classConstantMaskT')]
#[CoversFunction('Typhoon\Type\template')]
#[CoversFunction('Typhoon\Type\templateOut')]
#[CoversFunction('Typhoon\Type\templateIn')]
#[CoversFunction('Typhoon\Type\aliasT')]
#[CoversFunction('Typhoon\Type\intersectionT')]
#[CoversFunction('Typhoon\Type\andT')]
#[CoversFunction('Typhoon\Type\unionT')]
#[CoversFunction('Typhoon\Type\orT')]
#[CoversFunction('Typhoon\Type\nullOrT')]
#[CoversFunction('Typhoon\Type\isSubtypeT')]
#[CoversFunction('Typhoon\Type\isSupertypeT')]
#[CoversFunction('Typhoon\Type\ternaryT')]
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
        yield [intMaskT(orT(intT(1), intT(2), intT(4))), 'int-mask-of<1|2|4>'];
        yield [intMaskT(constantMaskT('JSON_*')), 'int-mask-of<const<JSON_*>>'];
        yield [floatT, 'float'];
        yield [floatT(0), '0.0'];
        yield [floatT('0'), '0.0'];
        yield [floatT(0.0), '0.0'];
        yield [floatT('0.0'), '0.0'];
        yield [floatT(0.234), '0.234'];
        yield [floatT('0.234'), '0.234'];
        yield [floatT(-0.234), '-0.234'];
        yield [floatT('-0.234'), '-0.234'];
        yield [floatT(1), '1.0'];
        yield [floatRangeT(), 'float<min, max>'];
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
        yield [arrayT(orT(intT, stringT)), 'array<mixed>'];
        yield [arrayT(orT(stringT, intT)), 'array<mixed>'];
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
        yield [namedObjectT(\ArrayObject::class), 'ArrayObject'];
        yield [namedObjectT(\ArrayObject::class, [arrayKeyT, stringT]), 'ArrayObject<array-key, string>'];
        yield [selfT, 'self'];
        yield [selfT([stringT]), 'self<string>'];
        yield [parentT, 'parent'];
        yield [parentT([stringT]), 'parent<string>'];
        yield [staticT, 'static'];
        yield [staticT([stringT]), 'static<string>'];
        yield [orT(intT, stringT), 'int|string'];
        yield [orT(intT, orT(stringT, floatT)), 'int|(string|float)'];
        yield [orT(intT, andT(stringT, floatT)), 'int|(string&float)'];
        yield [nullOrT(stringT), 'null|string'];
        yield [andT(intT, stringT), 'int&string'];
        yield [andT(intT, orT(stringT, floatT)), 'int&(string|float)'];
        yield [iterableT, 'iterable'];
        yield [iterableT(), 'iterable<mixed>'];
        yield [iterableT(value: stringT), 'iterable<string>'];
        yield [iterableT(stringT, intT), 'iterable<string, int>'];
        yield [callableT, 'callable'];
        yield [callableT(), 'callable(): mixed'];
        yield [callableT(return: UntypedT::T), 'callable(): untyped-mixed'];
        yield [callableT([template('T')]), 'callable<T>(): mixed'];
        yield [callableT(return: callableT()), 'callable(): (callable(): mixed)'];
        yield [callableT(return: voidT), 'callable(): void'];
        yield [callableT(params: [stringT]), 'callable(string): mixed'];
        yield [callableT(params: [param(type: stringT, default: true)]), 'callable(string=): mixed'];
        yield [callableT(params: [param(type: stringT, default: numericStringT)]), 'callable(string=numeric-string): mixed'];
        yield [callableT(params: [param(type: stringT, variadic: true)]), 'callable(string...): mixed'];
        yield [callableT(params: [param(type: stringT, byRef: true)]), 'callable(string&): mixed'];
        yield [callableT(params: [param(type: stringT, byRef: true, variadic: true)]), 'callable(string&...): mixed'];
        yield [callableT(params: [param('a', stringT, byRef: true, variadic: true)]), 'callable(string &...$a): mixed'];
        yield [closureT(), 'Closure(): mixed'];
        yield [closureT([template('T')]), 'Closure<T>(): mixed'];
        yield [closureT(return: closureT()), 'Closure(): (Closure(): mixed)'];
        yield [closureT(return: voidT), 'Closure(): void'];
        yield [closureT(params: [stringT]), 'Closure(string): mixed'];
        yield [closureT(params: [param(type: stringT, default: true)]), 'Closure(string=): mixed'];
        yield [closureT(params: [param(type: stringT, default: numericStringT)]), 'Closure(string=numeric-string): mixed'];
        yield [closureT(params: [param(type: stringT, variadic: true)]), 'Closure(string...): mixed'];
        yield [closureT(params: [param(type: stringT, byRef: true)]), 'Closure(string&): mixed'];
        yield [closureT(params: [param(type: stringT, byRef: true, variadic: true)]), 'Closure(string&...): mixed'];
        yield [closureT(params: [param('a', stringT, byRef: true, variadic: true)]), 'Closure(string &...$a): mixed'];
        yield [objectShapeT(), 'object{}'];
        yield [objectShapeT(['name' => stringT]), 'object{name: string}'];
        yield [objectShapeT(['name' => optional(stringT)]), 'object{name?: string}'];
        yield [constantT('JSON_THROW_ON_ERROR'), 'const<JSON_THROW_ON_ERROR>'];
        yield [constantMaskT('JSON_*'), 'const<JSON_*>'];
        yield [classConstantT(\stdClass::class, 'test'), 'stdClass::test'];
        yield [classConstantMaskT(\stdClass::class, 'test_*'), 'stdClass::test_*'];
        yield [keyT(arrayT), 'key-of<array>'];
        yield [valueT(arrayT), 'value-of<array>'];
        yield [offsetT(nonEmptyListT(), intT(0)), 'non-empty-list<mixed>[0]'];
        yield [ternaryT(trueT, then: intT, else: floatT), 'true ? int : float'];
        yield [isSubtypeT(trueT, mixedT), 'true is mixed'];
        yield [isSupertypeT(boolT, falseT), 'false is bool'];
        yield [aliasT(\stdClass::class, 'A'), 'stdClass@A'];
        yield [aliasT(\stdClass::class, 'A', [stringT]), 'stdClass@A<string>'];
        /** @phpstan-ignore argument.type */
        yield [callableT([template('T', type: $T)], [$T], nullOrT($T)), 'callable<T>(T): (null|T)'];
        yield [callableT([template('T', scalarT, stringT, type: $T)], [$T], $T), 'callable<T of scalar super string>(T): T'];
        yield [objectT([template('T', type: $T)], [namedObjectT(\stdClass::class, [intT])], ['p' => $T]), 'object<T>:stdClass<int>{p: T}'];
        yield [objectT([templateIn('I', default: intT), templateOut('O')]), 'object<in I = int, out O>{}'];
        yield [objectT([template('T', type: $T), template('T2', $T)]), 'object<T, T2 of T>{}'];
    }

    #[DoesNotPerformAssertions]
    public function testConstructorsCoverage(): void
    {
        iterator_to_array(self::provideCases(), preserve_keys: false);
    }

    public function testItCanBeExtended(): void
    {
        $myStringify = new /** @extends Fallback<non-empty-string> */ class extends Fallback {
            private readonly Stringify $stringify;

            public function __construct()
            {
                $this->stringify = new Stringify(\WeakReference::create($this));
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

        $string = listShapeT([listShapeT([intT])])->accept($myStringify);

        self::assertSame('list{list{INT}}', $string);
    }
}
