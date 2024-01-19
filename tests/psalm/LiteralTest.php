<?php

declare(strict_types=1);

namespace Typhoon\Type;

/** @psalm-check-type-exact $_intLiteral = 123 */
$_intLiteral = extractType(new LiteralType(123));

/** @psalm-check-type-exact $_negativeIntLiteral = -223 */
$_negativeIntLiteral = extractType(new LiteralType(-223));

/**
 * @return literal-int
 */
function generateLiteralInt(): int
{
    return 123;
}

/** @psalm-check-type-exact $_genericLiteralInt = literal-int */
$_genericLiteralInt = extractType(new LiteralType(generateLiteralInt()));

/**
 * @param LiteralType<1|2> $_type
 */
function testIntLiteralIsCovariant(LiteralType $_type): void {}

testIntLiteralIsCovariant(new LiteralType(1));

/** @psalm-check-type-exact $_floatLiteral = 3.5 */
$_floatLiteral = extractType(new LiteralType(3.5));

/** @psalm-check-type-exact $_negativeFloatLiteral = -1.222 */
$_negativeFloatLiteral = extractType(new LiteralType(-1.222));

/** @psalm-check-type-exact $_genericFloat = float */
$_genericFloat = extractType(new LiteralType(array_sum([1, 0.5])));

/**
 * @param LiteralType<0.5|-1.7> $_type
 */
function testFloatLiteralIsCovariant(LiteralType $_type): void {}

testFloatLiteralIsCovariant(new LiteralType(-1.7));

/** @psalm-check-type-exact $_stringLiteral = 'abc' */
$_stringLiteral = extractType(new LiteralType('abc'));

/**
 * @return literal-string
 */
function generateLiteralString(): string
{
    return 'abc';
}

/** @psalm-check-type-exact $_genericLiteralString = literal-string */
$_genericLiteralString = extractType(new LiteralType(generateLiteralString()));

/**
 * @param LiteralType<'abc'|'xyz'> $_type
 */
function testStringLiteralIsCovariant(LiteralType $_type): void {}

testStringLiteralIsCovariant(new LiteralType('abc'));

/** @psalm-check-type-exact $_classStringLiteral = stdClass::class */
$_classStringLiteral = extractType(new LiteralType(\stdClass::class));

/**
 * @param LiteralType<\Iterator::class|\IteratorAggregate::class> $_type
 */
function testClassStringLiteralIsCovariant(LiteralType $_type): void {}

testClassStringLiteralIsCovariant(new LiteralType(\Iterator::class));
