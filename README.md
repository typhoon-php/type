# PHP Extended Type System • Type

Collection of value objects that represent the types of PHP Extended Type System.
All the types are inspired by popular PHP static analysis tools: [Psalm](https://psalm.dev/) and [PHPStan](https://phpstan.org/).

This library will never have any dependencies. Once full and stable, it might be proposed as a [PSR](https://www.php-fig.org/psr/) or [PER](https://www.php-fig.org/per/).

Please, note that this is a low-level API for static analysers and reflectors. It's not designed for convenient general usage in a project.
For that purpose we plan to release a special package. 

## Installation

```
composer require extended-type-system/type
```

## Usage

```php
use ExtendedTypeSystem\Type\CallableType;
use ExtendedTypeSystem\Type\ClassConstantType;
use ExtendedTypeSystem\Type\ClassTemplateType;
use ExtendedTypeSystem\Type\FalseType;
use ExtendedTypeSystem\Type\FloatType;
use ExtendedTypeSystem\Type\IntType;
use ExtendedTypeSystem\Type\MixedType;
use ExtendedTypeSystem\Type\NamedObjectType;
use ExtendedTypeSystem\Type\NonEmptyListType;
use ExtendedTypeSystem\Type\NumericType;
use ExtendedTypeSystem\Type\Parameter;
use ExtendedTypeSystem\Type\ShapeElement;
use ExtendedTypeSystem\Type\ShapeType;
use ExtendedTypeSystem\Type\UnionType;
use ExtendedTypeSystem\Type\VoidType;

/**
 * array{
 *     a: non-empty-list,
 *     b?: int|float,
 *     c: Traversable<numeric-string, false>,
 *     d: callable(PDO::*, T:Generator=, mixed...): void,
 *     ...
 * }
 */
$type = new ShapeType(
    [
        'a' => new ShapeElement(new NonEmptyListType()),
        'b' => new ShapeElement(new UnionType(IntType::self, FloatType::self), optional: true),
        'c' => new ShapeElement(new NamedObjectType(
            class: Traversable::class,
            templateArguments: [NumericType::self, FalseType::self],
        )),
        'd' => new ShapeElement(new CallableType(
            parameters: [
                new Parameter(new ClassConstantType(PDO::class, '*')),
                new Parameter(new ClassTemplateType('TSend', Generator::class), hasDefault: true),
                new Parameter(MixedType::self, variadic: true),
            ],
            returnType: VoidType::self,
        )),
    ],
    sealed: false,
);
```
