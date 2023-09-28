<?php

declare(strict_types=1);

namespace Typhoon\Type\TruthyStringTest;

use Typhoon\Type\TruthyString;
use function Typhoon\Type\extractType;

/** @psalm-check-type-exact $_string = non-falsy-string */
/** @psalm-check-type-exact $_string = truthy-string */
$_string = extractType(TruthyString::type);
