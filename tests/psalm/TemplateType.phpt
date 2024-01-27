--FILE--
<?php

namespace Typhoon\Type;

$_type = PsalmTest::extractType(new TemplateType('T', new AtFunction('trim'), MixedType::type));
/** @psalm-check-type-exact $_type = \mixed */

--EXPECT--
