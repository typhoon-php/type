--FILE--
<?php

namespace Typhoon\Type;

$_type = PsalmTest::extractType(new SelfClassStringLiteralType(\stdClass::class));
/** @psalm-check-type-exact $_type = \class-string */

--EXPECT--
