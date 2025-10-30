# Untyped

## Problem

In PHP, native type declarations can be omitted in 3 contexts, resulting in untyped positions:

```php
class UntypedClass
{
    public /** untyped */ $property;

    public function method(/** untyped */ $parameter) /** untyped return */
    {
    }
}
```

A child class must still satisfy all variance rules:

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

One can pass any value to `untyped`:

```php
$object = new UntypedClass();
$object->property = 1;
$object->method('a'); // unknown return value -> mixed
```

It's important to distinguish `untyped` from `mixed` to be able to check variance rules correctly.

## Solution

Add an `untypedT` type that is a subtype of `mixed` and a supertype of all the other types.

Reduce `untypedT` to `mixed` in [Reduced](../src/Visitor/Reduced.php).

Do not add a constructor for this type — it’s not meant for regular use.
Advanced users can access it via the low-level API: [`UntypedT::T`](../src/UntypedT.php). 

Use `'untyped-mixed'` as its string representation to avoid collisions with the `untyped` class name.
