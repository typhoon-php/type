# ADR-002: Address Floating-Point Precision

## Status

Accepted

## Context

Typhoon defines two types that represent floating-point numbers using float literals: `FloatValueT` and `FloatRangeT`.

Both types may appear in PHPDoc annotations or be constructed programmatically:

```php
use function Typhoon\Type\floatT;
use function Typhoon\Type\floatRangeT;

/**
 * @param -0.21991 $value
 * @param float<0.5, 1234.8> $range
 */
function x(float $value, float $range): void {}

floatT(-0.21991);
floatRangeT(0.5, 1234.8);
```

When converting string float representations parsed from PHPDoc into native `float`, precision is lost due to
the inherent limitations of binary floating-point representation.

## Decision

Store float values inside `FloatValueT` and `FloatRangeT` using `Brick\Math\BigDecimal`. Although this introduces an
external dependency, `BigDecimal` provides a robust, type-safe, and precise representation that avoids floating-point
rounding errors and requires minimal maintenance.

## Considered Options

### 1. `numeric-string`

**Advantages**

* Retains full decimal precision.
* No external dependencies.
* Minimal implementation effort.

**Disadvantages**

* Allows scientific notation (e.g., `1e-5`).
* Requires external or custom logic for arithmetic and comparisons.

### 2. Value Object wrapping `numeric-string`

**Advantages**

* Retains full decimal precision.
* Can forbid scientific notation.
* No external dependencies.

**Disadvantages**

* Requires internal support and maintenance.
* Still needs external or custom implementations for numeric operations.

### 3. `brick/math`

**Advantages**

* Retains full decimal precision.
* Eliminates scientific notation inconsistencies.
* Provides a mature, feature-rich API.
* Supports multiple math backends and extensions.
* No need for custom logic or maintenance.

**Disadvantages**

* External dependency.

## Consequences

* Full decimal precision is preserved for all float literals and ranges.
* PHPDoc float literals are represented consistently and accurately.
* The project now depends on `brick/math`.
* Rational values (e.g., `1/3`) remain unsupported in type syntax.

## Implementation Details

* Use `Brick\Math\BigDecimal` internally in `FloatValueT` and `FloatRangeT`.
* Do **not** use `BigNumber`, as it includes `BigRational` (e.g., `1/3`), which cannot currently be expressed in
  PHPDoc syntax. Users who need rational arithmetic can handle it explicitly:

  ```php
  use Brick\Math\BigNumber;
  
  floatValueT(BigNumber::of('1/3')->toScale(10, RoundingMode::HALF_UP));
  ```
* In [`Stringify`](../src/Type/Visitor/Stringify.php), when stringifying floats with a zero scale, set the scale to `1`
  to visually distinguish floats from integers:

  ```php
  use Brick\Math\BigDecimal;
  use function Typhoon\Type\stringify;
  
  stringify(floatValueT(1)); // 1.0
  stringify(floatValueT(1.0)); // 1.0
  stringify(floatValueT(BigDecimal::one())); // 1.0
  
  // note that these numbers equal:
  var_dump(BigDecimal::one()->isEqualTo('1.0')); // true
  ```

## References

* [Brick\Math documentation](https://github.com/brick/math)

## Metadata

| Field              | Value                                                           |
|--------------------|-----------------------------------------------------------------|
| **Date**           | 2025-10-31                                                      |
| **Implemented in** | [#53](https://github.com/typhoon-php/type/pull/53)              |
| **Released in**    | [0.6.0](https://github.com/typhoon-php/type/releases/tag/0.6.0) |
| **Author**         | [@vudaltsov](https://github.com/vudaltsov)                      |
