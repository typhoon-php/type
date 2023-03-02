<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\php;

/** @psalm-check-type-exact $_doubleUnion = true|string */
$_doubleUnion = extractType(new UnionType(php::true, php::string));

/** @psalm-check-type-exact $_tripleUnion = true|string|int */
$_tripleUnion = extractType(new UnionType(php::true, php::string, php::int));

/**
 * @param UnionType<int|string|float> $_type
 */
function testUnionIsCovariant(UnionType $_type): void
{
}

testUnionIsCovariant(new UnionType(php::int, php::string));
