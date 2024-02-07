--FILE--
<?php

namespace Typhoon\Type;

$_type = PsalmTest::extractType(new ParentType(\stdClass::class));
/** @psalm-check-type-exact $_type = \object */

--EXPECT--
