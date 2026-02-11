# Typhoon Type

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/typhoon/type/php)](https://packagist.org/packages/typhoon/type)
[![GitHub Release](https://img.shields.io/github/v/release/typhoon-php/type)](https://github.com/typhoon-php/type/releases)
[![PHPStan](https://img.shields.io/badge/phpstan%20level-max-brightgreen.svg?style=flat&logo=php)](phpstan.dist.neon)
[![Code Coverage](https://codecov.io/gh/typhoon-php/type/branch/0.8.x/graph/badge.svg)](https://codecov.io/gh/typhoon-php/type/tree/0.8.x)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Ftyphoon-php%2Ftype%2F0.8.x)](https://dashboard.stryker-mutator.io/reports/github.com/typhoon-php/type/0.8.x)

Typhoon Type is an object abstraction over the modern PHP type system. Use this library to build tools that work with
sophisticated types:

```php
use function Typhoon\Type\arrayShapeT;
use function Typhoon\Type\objectT;
use function Typhoon\Type\nonEmptyListT;

$json = <<<JSON
    {
        "people": [
            {"name": "Valentin"},
            {"name": "Andrey"}
        ],
    }
    JSON;
    
final readonly class Person
{
    public function __construct(
        public string $name,
    ) {}    
}

$type = arrayShapeT([
    'people' => nonEmptyListT(objectT(Person::class)),
]);

$request = new Mapper()->map($json, $type);
```

## Installation

```
composer require typhoon/type
```

## Printing types

To print any type, use `stringify()`:

```php
use function Typhoon\Type\stringify;

$type = arrayShapeT([
    'people' => nonEmptyListT(objectT(Person::class)),
]);

var_dump(stringify($type));

// array{'people': non-empty-list<Person>}
```

## Supported types

### Native

| Typhoon                                                                 | Native                  |
|-------------------------------------------------------------------------|-------------------------|
| `nullT`                                                                 | `null`                  |
| `voidT`                                                                 | `void`                  |
| `neverT`                                                                | `never`                 |
| `falseT`                                                                | `false`                 |
| `trueT`                                                                 | `true`                  |
| `boolT`                                                                 | `bool`                  |
| `intT`                                                                  | `int`                   |
| `floatT`                                                                | `float`                 |
| `stringT`                                                               | `string`                |
| `arrayT`                                                                | `array`                 |
| `objectT`                                                               | `object`                |
| `objectT(Foo::class)`                                                   | `Foo`                   |
| `selfT`                                                                 | `self`                  |
| `parentT`                                                               | `parent`                |
| `staticT`                                                               | `static`                |
| `iterableT`                                                             | `iterable`              |
| `callableT`                                                             | `callable`              |
| `resourceT`                                                             | `resource`              |
| `nullOrT(stringT)`                                                      | `?string`               |
| `unionT(intT, stringT)`                                                 | `int\|string`           |
| `intersectionT(objectT(Countable::class), objectT(Traversable::class))` | `Countable&Traversable` |
| `mixedT`                                                                | `mixed`                 |

### PHPDoc numbers

| Typhoon                                            | PHPDoc                    |
|----------------------------------------------------|---------------------------|
| `intT(123)`                                        | `123`                     |
| `positiveIntT`                                     | `positive-int`            |
| `negativeIntT`                                     | `negative-int`            |
| `nonPositiveIntT`                                  | `non-positive-int`        |
| `nonNegativeIntT`                                  | `non-negative-int`        |
| `nonZeroIntT`                                      | `non-zero-int`            |
| `intRangeT(-5, 6)`                                 | `int<-5, 6>`              |
| `intRangeT(max: 6)`                                | `int<min, 6>`             |
| `intRangeT(min: -5)`                               | `int<-5, max>`            |
| `intMaskT(1, 2, 4)`                                | `int-mask<1, 2, 4>`       |
| `intMaskT(classConstantMaskT(Foo::class, 'INT_*')` | `int-mask-of<Foo::INT_*>` |
| `floatT(12.5)`                                     | `12.5`                    |
| `floatRangeT(-0.001, 2.344)`                       | `float<-0.001, 2.344>`    |

### PHPDoc strings

| Typhoon                                                                         | PHPDoc                              |
|---------------------------------------------------------------------------------|-------------------------------------|
| `nonEmptyStringT`                                                               | `non-empty-string`                  |
| `truthyStringT`, `nonFalsyStringT`                                              | `truthy-string`, `non-falsy-string` |
| `numericStringT`                                                                | `numeric-string`                    |
| `lowercaseString`                                                               | `lowercase-string`                  |
| `stringT('abc')`                                                                | `'abc'`                             |
| `classT(Foo::class)`, `classStringT(Foo::class)`, `classT(objectT(Foo::class))` | `class-string<Foo>`                 |
| `stringT(Foo::class)`, `classConstantT(Foo::class, 'class')`                    | `Foo::class`                        |
| `literalStringT`                                                                | `literal-string`                    |

### PHPDoc constants

| Typhoon                                  | PHPDoc        |
|------------------------------------------|---------------|
| `constantT('PHP_INT_MAX')`               | `PHP_INT_MAX` |
| `constantMaskT('JSON_*')`                | `JSON_*`      |
| `classConstantT(Foo::class, 'BAR')`      | `Foo::BAR`    |
| `classConstantMaskT(Foo::class, 'IS_*')` | `Foo::IS_*`   |

### PHPDoc arrays and iterables

| Typhoon                                         | PHPDoc                               |
|-------------------------------------------------|--------------------------------------|
| `arrayT(value: objectT(Foo::class))`            | `Foo[]`                              |
| `listT(stringT)`                                | `list<string>`                       |
| `nonEmptyListT(stringT)`                        | `non-empty-list<string>`             |
| `listShapeT([intT, stringT])`                   | `list{int, string}`                  |
| `arrayShapeT([intT, optional(stringT)])`        | `list{int, 1?: string}`              |
| `unsealedListShapeT([intT])`                    | `list{int, ...}`                     |
| `unsealedListShapeT([intT], stringT)`           | `list{int, ...<string>}`             |
| `arrayT(value: stringT)`                        | `array<string>`                      |
| `arrayT(intT, stringT)`                         | `array<int, string>`                 |
| `nonEmptyArrayT(arrayKeyT, stringT)`            | `non-empty-array<array-key, string>` |
| `arrayShapeT()`                                 | `array{}`                            |
| `arrayShapeT([intT, stringT])`                  | `array{int, string}`                 |
| `arrayShapeT([intT, 'a' => optional(stringT)])` | `array{int, a?: string}`             |
| `unsealedArrayShapeT([intT])`                   | `array{int, ...}`                    |
| `unsealedArrayShapeT([floatT], intT, stringT)`  | `array{float, ...<int, string>}`     |
| `keyOfT(classConstantT(Foo::class, 'ARRAY'))`   | `key-of<Foo::ARRAY>`                 |
| `valueOfT(classConstantT(Foo::class, 'ARRAY'))` | `value-of<Foo::ARRAY>`               |
| `offsetT($TArray->type, $TKey->type)`           | `TArray[TKey]`                       |
| `iterableT(objectT, stringT)`                   | `iterable<object, string>`           |
| `iterableT(value: stringT)`                     | `iterable<string>`                   |

### PHPDoc objects

| Typhoon                                        | PHPDoc                  |
|------------------------------------------------|-------------------------|
| `objectT(Foo::class, [stringT, floatT])`       | `Foo<string, float>`    |
| `selfT([stringT, floatT])`                     | `self<string, float>`   |
| `parentT([stringT, floatT])`                   | `parent<string, float>` |
| `staticT([stringT, floatT])`                   | `static<string, float>` |
| `objectShapeT(['prop' => stringT])`            | `object{prop: string}`  |
| `objectShapeT(['prop' => optional(stringT))])` | `object{prop?: string}` |

### PHPDoc callables

| Typhoon                                       | PHPDoc                       |
|-----------------------------------------------|------------------------------|
| `callableT([stringT], voidT)`                 | `callable(string): void`     |
| `callableT([param(stringT, default: true)])`  | `callable(string=): mixed`   |
| `callableT([param(stringT, variadic: true)])` | `callable(string...): mixed` |
| `callableT([param(stringT, byRef: true)])`    | `callable(string&): mixed`   |
| `closureT([stringT], voidT)`                  | `Closure(string): void`      |
| `closureT([param(stringT, default: true)])`   | `Closure(string=): mixed`    |
| `closureT([param(stringT, variadic: true)])`  | `Closure(string...): mixed`  |
| `closureT([param(stringT, byRef: true)])`     | `Closure(string&): mixed`    |

### Union aliases

| Typhoon     | PHPDoc      |
|-------------|-------------|
| `array-key` | `arrayKeyT` |
| `numericT`  | `numeric`   |
| `scalarT`   | `scalar`    |
