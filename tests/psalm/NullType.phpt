--FILE--
<?php

namespace Typhoon\Type;

$_type = PsalmTest::extractType(NullType::type);
/** @psalm-check-type-exact $_type = null */

--EXPECT--
