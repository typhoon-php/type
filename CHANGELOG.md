# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.6.0]

### Added

- **[BC Break:]** Add an `untyped` type, see [ADR](adr/001_untyped.md).

### Changed

- **BC Break:** Use `BigDecimal` for floats, see [002_float_precision.md](adr/002_float_precision.md).

## [0.5.1]

### Added

- Add a reduced representation for the `mixed` type.

### Fixed

- Stringify alias template arguments.
- Stringified float never looks like an int anymore: .0 is added when needed.

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
