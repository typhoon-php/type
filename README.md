# PHP Extended Type System • Type

Collection of value objects that represent the types of PHP Extended Type System.
Currently, all the types are inspired by popular PHP static analysis tools: [Psalm](https://psalm.dev/) and [PHPStan](https://phpstan.org/).

This library will never have any dependencies. Once full and stable, it might be proposed as a [PSR](https://www.php-fig.org/psr/) or [PER](https://www.php-fig.org/per/).

## Installation

```
composer require extended-type-system/type
```

## Usage

```php
use ExtendedTypeSystem\php;

/** 
 * array{
 *     a:  string,
 *     b?: int,
 *     c:  Traversable<string, false>,
 *     d:  callable(float, T:Generator): void,
 *     ...
 * }
 */
$type = php::unsealedShape([
    'a' => php::string,
    'b' => php::optionalKey(php::int),
    'c' => php::objectOf(Traversable::class, php::string, php::false),
    'd' => php::callable([php::float, php::classTemplate('TSend', Generator::class)], php::void),
]);
```

## Do not implement Type interface

All implementations of the `Type` interface should be treated as sealed.
`Type` interface MUST NOT be implemented in userland!
If you need an alias for a complex type, extend `TypeAlias`.
