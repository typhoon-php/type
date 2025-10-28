# Typhoon Type

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/typhoon/type/php)](https://packagist.org/packages/typhoon/type)
[![GitHub Release](https://img.shields.io/github/v/release/typhoon-php/type)](https://github.com/typhoon-php/type/releases)
[![Code Coverage](https://codecov.io/gh/typhoon-php/type/branch/0.5.x/graph/badge.svg)](https://codecov.io/gh/typhoon-php/type/tree/0.5.x)

Typhoon Type is an object abstraction over the modern PHP type system. Use this library to build tools that work with
sophisticated types.

Here are some examples of potential use-cases:

```php
use function Typhoon\Type\arrayShapeT;
use function Typhoon\Type\namedObjectT;
use function Typhoon\Type\nonEmptyListT;

$json = <<<JSON
    {
        "people": [
            {"name": "Valentin"},
            {"name": "Andrey"}
        ],
    }
    JSON;


$request = new Mapper()->map($json, arrayShapeT([
    'people' => nonEmptyListT(namedObjectT(People::class)),
]));
```

## Installation

```
composer require typhoon/type
```

## Constructing types

Typhoon types can be constructed via the `Typhoon\Type\*` constants and functions. Let's express the `flip()` function
using our DSL:

```php
use function Typhoon\Type\callableT;
use function Typhoon\Type\template;

/**
 * @template X
 * @template Y
 * @template Z
 * @param callable(X, Y): Z $fn
 * @return callable(Y, X): Z
 */
$flip = static fn (callable $fn)
     => static fn (mixed $y, mixed $x) => $fn($x, $y);

$flipType = callableT(
    templates: [
        $X = template('X'),
        $Y = template('Y'),
        $Z = template('Z'),
    ],
    params: [callableT(params: [$X->type, $Y->type], return: $Z->type)],
    return: callableT(params: [$Y->type, $X->type], return: $Z->type),
);
```

## Printing types

To cast any type to string, use the `Typhoon\Type\stringify()` function:

```php
use function Typhoon\Type\stringify;

var_dump(stringify($flipType));

// callable<X, Y, Z>(callable(X, Y): Z): (callable(Y, X): Z)
```

## Supported types

### Native

| Native                  | Typhoon                                                                                                                                                                                                                                                                                       |
|-------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `null`                  | `nullT`                                                                                                                                                                                                                                                                                       |
| `void`                  | `voidT`                                                                                                                                                                                                                                                                                       |
| `never`                 | `neverT`                                                                                                                                                                                                                                                                                      |
| `false`                 | `falseT`                                                                                                                                                                                                                                                                                      |
| `true`                  | `trueT`                                                                                                                                                                                                                                                                                       |
| `bool`                  | `boolT`                                                                                                                                                                                                                                                                                       |
| `int`                   | `intT`                                                                                                                                                                                                                                                                                        |
| `float`                 | `floatT`                                                                                                                                                                                                                                                                                      |
| `string`                | `stringT`                                                                                                                                                                                                                                                                                     |
| `array`                 | `arrayT`                                                                                                                                                                                                                                                                                      |
| `object`                | `objectT`                                                                                                                                                                                                                                                                                     |
| `Foo`                   | `namedObjectT(Foo::class)`                                                                                                                                                                                                                                                                    |
| `self`                  | `selfT`                                                                                                                                                                                                                                                                                       |
| `parent`                | `parentT`                                                                                                                                                                                                                                                                                     |
| `static`                | `staticT`                                                                                                                                                                                                                                                                                     |
| `iterable`              | `iterableT`                                                                                                                                                                                                                                                                                   |
| `callable`              | `callableT`                                                                                                                                                                                                                                                                                   |
| `Closure`               | `closureT` or `namedObjectT(Closure::class)`                                                                                                                                                                                                                                                  |
| `resource`              | `resourceT`                                                                                                                                                                                                                                                                                   |
| `?string`               | `nullOrT(stringT)`                                                                                                                                                                                                                                                                            |
| `int\|string`           | `unionT(intT, stringT)`, `orT(intT, stringT)`                                                                                                                                                                                                                                                 |
| `Countable&Traversable` | <code>intersectionT(<br>&nbsp;&nbsp;&nbsp;&nbsp;namedObjectT(Countable::class),<br>&nbsp;&nbsp;&nbsp;&nbsp;namedObjectT(Traversable::class),<br>)<br>orT(<br>&nbsp;&nbsp;&nbsp;&nbsp;namedObjectT(Countable::class),<br>&nbsp;&nbsp;&nbsp;&nbsp;namedObjectT(Traversable::class),<br>)</code> |
| `mixed`                 | `mixedT`                                                                                                                                                                                                                                                                                      |

### PHPDoc numbers

| PHPStan                   | Psalm                        | Typhoon                                            |
|---------------------------|------------------------------|----------------------------------------------------|
| ❌                         | `literal-int`                | `literalT(intT)`                                   |
| `123`                     | `123`                        | `intT(123)`                                        |
| `positive-int`            | `positive-int`               | `positiveIntT`                                     |
| `negative-int`            | `negative-int`               | `negativeIntT`                                     |
| `non-positive-int`        | `non-positive-int`           | `nonPositiveIntT`                                  |
| `non-negative-int`        | `non-negative-int`           | `nonNegativeIntT`                                  |
| `non-zero-int`            | `negative-int\|positive-int` | `nonZeroIntT`                                      |
| `int<-5, 6>`              | `int<-5, 6>`                 | `intRangeT(-5, 6)`                                 |
| `int<min, 6>`             | `int<min, 6>`                | `intRangeT(max: 6)`                                |
| `int<-5, max>`            | `int<-5, max>`               | `intRangeT(min: -5)`                               |
| `int-mask<1, 2, 4>`       | `int-mask<1, 2, 4>`          | `intMaskT(1, 2, 4)`                                |
| `int-mask-of<Foo::INT_*>` | `int-mask-of<Foo::INT_*>`    | `intMaskT(classConstantMaskT(Foo::class, 'INT_*')` |
| ❌                         | ❌                            | `floatRangeT(-0.001, 2.344)`                       |
| `12.5`                    | `12.5`                       | `floatT(12.5)`                                     |
| `numeric`                 | `numeric`                    | `numericT`                                         |

### PHPDoc strings

| PHPStan                             | Psalm                               | Typhoon                                                      |
|-------------------------------------|-------------------------------------|--------------------------------------------------------------|
| `non-empty-string`                  | `non-empty-string`                  | `nonEmptyStringT`                                            |
| `truthy-string`, `non-falsy-string` | `truthy-string`, `non-falsy-string` | `truthyStringT`, `nonFalsyStringT`                           |
| `numeric-string`                    | `numeric-string`                    | `numericStringT`                                             |
| `lowercase-string`                  | `lowercase-string`                  | `lowercaseString`                                            |
| `'abc'`                             | `'abc'`                             | `stringT('abc')`                                             |
| `class-string<Foo>`                 | `class-string<Foo>`                 | `classT(Foo::class))`, `classStringT(Foo::class))`           |
| `Foo::class`                        | `Foo::class`                        | `stringT(Foo::class)`, `classConstantT(Foo::class, 'class')` |
| ❌                                   | `interface-string`                  | ❌                                                            |
| ❌                                   | `trait-string`                      | ❌                                                            |
| ❌                                   | `enum-string`                       | ❌                                                            |
| ❌                                   | `lowercase-string`                  | ❌                                                            |
| `literal-string`                    | `literal-string`                    | `literalStringT`                                             |
| `callable-string`                   | `callable-string`                   | `andT(callableT, stringT)`                                   |

### PHPDoc constants

| PHPStan       | Psalm       | Typhoon                                  |
|---------------|-------------|------------------------------------------|
| `PHP_INT_MAX` | ❌           | `constantT('PHP_INT_MAX')`               |
| `Foo::BAR`    | `Foo::BAR`  | `classConstantT(Foo::class, 'BAR')`      |
| `Foo::IS_*`   | `Foo::IS_*` | `classConstantMaskT(Foo::class, 'IS_*')` |

### PHPDoc arrays and iterables

| PHPStan                              | Psalm                                | Typhoon                                         |
|--------------------------------------|--------------------------------------|-------------------------------------------------|
| `array-key`                          | `array-key`                          | `arrayKeyT`                                     |
| `Foo[]`                              | `Foo[]`                              | `arrayT(value: objectT(Foo::class))`            |
| `list<string>`                       | `list<string>`                       | `listT(stringT)`                                |
| `non-empty-list<string>`             | `non-empty-list<string>`             | `nonEmptyListT(stringT)`                        |
| `list{int, string}`                  | `list{int, string}`                  | `listShapeT([intT, stringT])`                   |
| `list{int, 1?: string}`              | `list{int, 1?: string}`              | `arrayShapeT([intT, optional(stringT)])`        |
| `list{int, ...}`                     | `list{int, ...}`                     | `unsealedListShapeT([intT])`                    |
| ❌                                    | `list{int, ...<string>}`             | `unsealedListShapeT([intT], stringT)`           |
| `array<string>`                      | `array<string>`                      | `arrayT(value: stringT)`                        |
| `array<int, string>`                 | `array<int, string>`                 | `arrayT(intT, stringT)`                         |
| `non-empty-array<array-key, string>` | `non-empty-array<array-key, string>` | `nonEmptyArrayT(arrayKeyT, stringT)`            |
| `array{}`                            | `array{}`                            | `arrayShapeT()`                                 |
| `array{int, string}`                 | `array{int, string}`                 | `arrayShapeT([intT, stringT])`                  |
| `array{int, a?: string}`             | `array{int, a?: string}`             | `arrayShapeT([intT, 'a' => optional(stringT)])` |
| `array{int, ...}`                    | `array{int, ...}`                    | `unsealedArrayShapeT([intT])`                   |
| ❌                                    | `array{float, ...<int, string>}`     | `unsealedArrayShapeT([floatT], intT, stringT)`  |
| `key-of<Foo::ARRAY>`                 | `key-of<Foo::ARRAY>`                 | `keyOfT(classConstantT(Foo::class, 'ARRAY'))`   |
| `value-of<Foo::ARRAY>`               | `value-of<Foo::ARRAY>`               | `valueOfT(classConstantT(Foo::class, 'ARRAY'))` |
| `TArray[TKey]`                       | `TArray[TKey]`                       | `offsetT($TArray->type, $TKey->type)`           |
| `iterable<object, string>`           | `iterable<object, string>`           | `iterableT(objectT, stringT)`                   |
| `iterable<string>`                   | `iterable<string>`                   | `iterableT(value: stringT)`                     |
| `callable&array`                     | `callable-array`                     | `andT(callableT, arrayT)`                       |

### PHPDoc objects

| PHPStan                           | Psalm                   | Typhoon                                               |
|-----------------------------------|-------------------------|-------------------------------------------------------|
| `Foo<string, float>`              | `Foo<string, float>`    | `namedObjectT(Foo::class, [stringT, floatT])`         |
| `self<string, float>`             | `self<string, float>`   | `selfT([stringT, floatT])`                            |
| `parent<string, float>`           | `parent<string, float>` | `parentT([stringT, floatT])`                          |
| `static<string, float>`           | `static<string, float>` | `staticT([stringT, floatT])`                          |
| `object{prop: string}`            | `object{prop: string}`  | `objectShapeT(['prop' => stringT])`                   |
| `object{prop?: string}`           | `object{prop?: string}` | `objectShapeT(['prop' => optional(stringT))])`        |
| ❌ (could be `object<T>{prop: T}`) | ❌                       | `objectT([$T = template('T')], ['prop' => $T->type])` |

### PHPDoc callables

| PHPStan                             | Psalm                        | Typhoon                                                          |
|-------------------------------------|------------------------------|------------------------------------------------------------------|
| `callable-string`                   | `callable-string`            | `andT(callableT, stringT)`                                       |
| `callable&array`                    | `callable-array`             | `andT(callableT, arrayT)`                                        |
| `callable(string): void`            | `callable(string): void`     | `callableT(params: [stringT], return: voidT)`                    |
| `callable(string=): mixed`          | `callable(string=): mixed`   | `callableT(params: [paramT(stringT, default: true)])`            |
| ❌ (could be `callable(string='a')`) | ❌                            | `callableT(params: [paramT(stringT, default: stringT('a'))])`    |
| `callable(...string): mixed`        | `callable(...string): mixed` | `callableT(params: [paramT(stringT, variadic: true)])`           |
| `callable(&string): mixed`          | `callable(&string): mixed`   | `callableT(params: [paramT(stringT, byRef: true)])`              |
| `callable<T>(T): ?T`                | ❌                            | `callableT([$T = template('T')], [$T->type], nullOrT($T->type))` |
| `pure-callable`                     | `pure-callable`              | ❌                                                                |
| `Closure` types                     | `Closure` types              | same as above via `closureT(...)`                                |

### Other PHPDoc types

| PHPStan                        | Psalm                           | Typhoon                                                  |
|--------------------------------|---------------------------------|----------------------------------------------------------|
| `scalar`                       | `scalar`                        | `scalarT`                                                |
| Alias `X`                      | Alias `X`                       | `aliasT(Foo::class, 'X')`                                |
| `(T is string ? true : false)` | `(T is string ? true : false)`  | `ternaryT(isSubtypeT($T->type, stringT), trueT, falseT)` |
| ❌                              | `properties-of<T>`              | ❌                                                        |
| ❌                              | `class-string-map<T of Foo, T>` | ❌                                                        |
| `open-resource`                | `open-resource`                 | ❌                                                        |
| `closed-resource`              | `closed-resource`               | ❌                                                        |
