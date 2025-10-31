# ADR-001: Untyped

## Accepted

Proposed

## Context

In PHP, native type declarations can be omitted in three contexts, producing **untyped** positions:

```php
class UntypedClass
{
    public /** untyped */ $property;

    public function method(/** untyped */ $parameter) /** untyped return */
    {
    }
}
```

Despite the absence of type declarations, **variance rules still apply** to child classes:

```php
class TypedClass extends UntypedClass
{
    public /** invariant */ $property;

    public function method(/** contravariant */ mixed $parameter): string /** covariant */
    {
        return 'a';
    }
}
```

An untyped position accepts **any** value, and the result type is effectively `mixed`:

```php
$object = new UntypedClass();
$object->property = 1;
$object->method('a'); // unknown return value → mixed
```

It is important to **distinguish** `untyped` from `mixed` to correctly enforce variance rules.

## Decision

Introduce a dedicated `untypedT` type that:

* is a **subtype of** `mixed`,
* is a **supertype of** all other types.

This ensures accurate type variance checking between typed and untyped members.

## Consequences

* Variance rules are now enforced correctly for untyped declarations.
* Static analysis can differentiate between “explicitly mixed” and “untyped”.

## Implementation Details

* Add `untypedT` as a new type in the system.
* Reduce it to `mixed` during normalization in [`Reduced`](../src/Type/Visitor/Reduced.php).
* Do **not** add a public constructor — this type is not intended for regular use.
  Advanced users can access it through the low-level API: [`UntypedT::T`](../src/Type/UntypedT.php).
* Use `'untyped-mixed'` as its string representation to avoid naming collisions with the `untyped` class name.

## References

* [PHP covariance and contravariance rules](https://www.php.net/manual/en/language.oop5.variance.php)

## Metadata

| Field              | Value                                                                                             |
|--------------------|---------------------------------------------------------------------------------------------------|
| **Date**           | 2025-10-31                                                                                        |
| **Implemented in** | [#52](https://github.com/typhoon-php/type/pull/52)                                                |
| **Released in**    | [0.6.0](https://github.com/typhoon-php/type/releases/tag/0.6.0)                                   |
| **Author**         | [@vudaltsov](https://github.com/vudaltsov), [@klimick](https://github.com/klimick)                |
