# ADR-003: Bare callable type has no sound PHPDoc representation

## Status

Proposed

## Context

PHP has a native `callable` type declaration. In PHPDoc it can be refined with templates, parameter types, and a return
type, for example: `callable<T>(T): ?T`.

The question is whether the native `callable` type has a sound representation in PHPDoc.

## Decision

There is **no sound PHPDoc representation** for the native `callable` type that both:

- accepts any callable value, and
- prevents runtime errors.

As a result, `CallableDefaultT` (representing the bare callable) **cannot be converted** to `CallableT`.
Static analyzers must handle `CallableDefaultT` explicitly, e.g. by emitting an error such as:

> Do not call an untyped callable.

## Considered Options

We need a PHPDoc type that accepts any callable yet prevents runtime errors.

### 1. `callable(): mixed`

```php
/**
 * @param callable(): mixed $callable
 */
function call(callable $callable): void
{
    // [PHPStan] OK
    // [Runtime] ArgumentCountError: Too few arguments to function
    $callable();
}

// [PHPStan] Parameter #1 $callable of function call expects callable(): mixed, Closure(string): 1 given.
call(static fn (string $a): int => 1);
```

✅ Detects arity mismatch.
❌ Too restrictive — does not allow passing arbitrary callables.

### 2. `callable(never...): mixed`

```php
/**
 * @param callable(never...): mixed $callable
 */
function call(callable $callable): void
{
    // [PHPStan] OK
    // [Runtime] ArgumentCountError: Too few arguments to function
    $callable();
}

// [PHPStan] Parameter #1 $callable of function call expects callable(never...): mixed, Closure(string): 1 given
call(static fn (string $a): int => 1);
```

✅ Detects mismatch in parameters.
❌ Too restrictive — does not allow passing arbitrary callables.

### 3. `callable(never): mixed`

```php
/**
 * @param callable(never): mixed $callable
 */
function call(callable $callable): void
{
    // [PHPStan] Callable callable(never): mixed invoked with 0 parameters, 1 required.
    // [Runtime] ArgumentCountError: Too few arguments to function
    $callable();
}

// [PHPStan] OK
call(static fn (string $a): int => 1);
```

✅ Detects invalid invocation.
❌ Callables with two or more parameters no longer match:

```php
/**
 * @param callable(never): mixed $callable
 */
function call(callable $callable): void
{
    // [PHPStan] Callable callable(never): mixed invoked with 0 parameters, 1 required.
    // [Runtime] ArgumentCountError: Too few arguments to function
    $callable();
}

// [PHPStan] Parameter #1 $callable of function call expects callable(never): mixed, Closure(string, int): 1 given.
call(static fn (string $a, int $b): int => 1);
```

### 4. No sound representation

The native `callable` type cannot be expressed soundly in PHPDoc. Any attempt either allows unsound calls or
excludes valid ones.

Therefore, it’s safer to treat the bare callable as **existential**: it can be stored and passed around,
but **must not be called** without explicit refinement.

## Implementation Details

* Remove `CallableDefaultT` from [`Reduced`](../src/Visitor/Reduced.php).
* Static analyzers should treat invocations of untyped callables as unsafe.
