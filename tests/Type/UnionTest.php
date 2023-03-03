<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

/** @psalm-check-type-exact $_doubleUnion = true|string */
$_doubleUnion = extractType(new UnionType(TrueType::self, StringType::self));

/** @psalm-check-type-exact $_tripleUnion = true|string|int */
$_tripleUnion = extractType(new UnionType(TrueType::self, StringType::self, IntType::self));

/**
 * @param UnionType<int|string|float> $_type
 */
function testUnionIsCovariant(UnionType $_type): void
{
}

testUnionIsCovariant(new UnionType(IntType::self, StringType::self));
