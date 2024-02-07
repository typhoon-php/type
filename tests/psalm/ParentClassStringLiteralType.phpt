--FILE--
<?php

namespace Typhoon\Type;

$_type = PsalmTest::extractType(new ParentClassStringLiteralType(\stdClass::class));
/** @psalm-check-type-exact $_type = \class-string */

--EXPECT--
