<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\php;

/** @psalm-check-type-exact $_literalString = literal-string */
$_literalString = extractType(php::literalString);
