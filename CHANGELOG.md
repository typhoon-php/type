# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.7.0] 2025-11-20

In this release, we refocus the library: `typhoon/type` now contains only the types necessary for building
runtime tools like `is()` or `HMap`. Complex types requiring reflection and/or additional context have been removed
and will be later implemented in `typhoon/algebra`.

### Added

- Add `WeakVisitor`.

### Changed

- **BC Break:** Rename `Mask::match()` to `test()`.
- **BC Break:** Use `float` in `FloatValueT` and `FloatRangeT`.
- **BC Break:** Use nullable limits in `IntRangeT`.
- **BC Break:** Use `K`, `V` templates in `ArrayT`.
- **BC Break:** Remove `$templates` from `CallableT` and `ClosureT`.
- **BC Break:** Simplify `Parameter::__construct()` signature.
- **BC Break:** Make `Reduce` a trait.
- **BC Break:** Rename `ObjectBareT` to `ObjectT`.
- **BC Break:** Require at least one type in `intersectionT`, return `IntersectionT`.
- **BC Break:** Require at least one type in `unionT`, return `UnionT`.
- **BC Break:** Require at least one type in `bitmaskT`.
- **BC Break:** Do not accept `WeakReference<Visitor>` in `Stringify` — use `WeakVisitor` instead.
- **BC Break:** Rename `namedObjectT()` to `objectT()`.
- Allow to pass `Mask` instance to `constantMaskT()` and `classConstantMaskT()`.

### Removed

- **BC Break:** Remove `Mask::toString()`, use `Mask::$mask` instead.
- **BC Break:** Remove `fromReflection()` function, will be later implemented in a separate package.
- **BC Break:** Remove `Template` and `Variance`, will be later implemented in algebra.
- **BC Break:** Remove `Alias` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `IsSubtype` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `KeyOf` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `LiteralString` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `ObjectT` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Offset` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `ParentBare` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `SelfBare` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Self` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `StaticBare` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Static` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Template` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Ternary` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `Untyped` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `ValueOf` and corresponding visitor methods, will be later implemented in algebra.
- **BC Break:** Remove `andT()` and `orT()`.

## [0.6.0] 2025-11-05

### Accepted ADRs
 
- [ADR-001: Untyped](adr/001_untyped.md)
- [ADR-002: Address Floating-Point Precision](adr/002_float_precision.md)
- [ADR-003: Bare callable type has no sound PHPDoc representation](adr/003_bare_callable.md)

### Fixed

- Stringify alias template arguments.
- Add `.0` to stringified floats with zero scale.

### Added

- Add a reduced representation for the `mixed` type.
- **BC Break:** Add an `untyped` type ([ADR](adr/001_untyped.md)).
- Add `fromReflection(?ReflectionType): Type` function.

### Changed

- **BC Break:** Use `BigDecimal` for floats ([ADR](adr/002_float_precision.md)).
- **BC Break:** Move `Typhoon\Type\Type` interface to `Typhoon\Type` ([#51](https://github.com/typhoon-php/type/pull/51)).
- **BC Break:** Use `PHP_INT_MIN`, `PHP_INT_MAX` for `IntRangeT` limits instead of `null`.
- **BC Break:** Rename `*DefaultT` to `*BareT`, `Visitor::*DefaultT()` to `Visitor::*BearT()` ([#56](https://github.com/typhoon-php/type/pull/56)).
- **BC Break:** Move `Reduced::callableBareT()` to `Fallback::callableBareT()` ([ADR](adr/003_bare_callable.md)).
- **BC Break:** Make `Stringify` final and allow composition via a `Visitor<non-empty-string>` parameter ([#54](https://github.com/typhoon-php/type/pull/54)).
- **BC Break:** Add `Template::new()`, `Template::factory()` constructors, make primary private.

### Removed

- **BC Break:** Remove `Reduced::visit()` and `visitMultiple()` — let developer implement them.
- **BC Break:** Remove `of()` for now — it cannot infer types correctly anyway.

## [0.5.0] 2025-10-30

The library was rewritten from scratch with a new philosophy in mind:

### Changed

- Extract `types` constants to global constants: `types::int` -> `intT`.
- Extract `types` methods to functions: `types::intRange()` -> `intRangeT()`.
- Make `Type` classes public.
- Rename `TypeVisitor` to `Visitor`, pass types as objects without destructuring.
- Rename `TypeStringifier` to `Stringify`, make it abstract.
- Rename `DefaultTypeVisitor` to `Fallback`.

### Added

- `Reduced` visitor that expresses some types via the others, reducing the number of `Visitor` methods to implement. 
- Support for generics in anonymous functions and objects. 

## [0.4.4] 2024-08-18

### Added

- Add `Parameter::with()`.
- Add `ShapeElement::with()`.

### Changed

- Make `ShapeElement::__construct($type)` optional with `types::mixed` as a default value.

## [0.4.3] 2024-08-06

### Added

- Add `types::value()` factory that creates a `Type` from an arbitrary value.

### Deprecated

- Deprecate `types::scalar()` in favor of `value()`.

## [0.4.2] 2024-08-05

### Changed

- Drop needless `$type` parameter PHPDoc types in `TypeVisitor`.
- Return `Type<int>` in `types::intMask()` due to possibly overflowing bitmasks.

## [0.4.1] 2024-08-05

### Fixed

- Replace self, parent and static type arguments in RecursiveTypeReplacer.
