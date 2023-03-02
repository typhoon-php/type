<?php

declare(strict_types=1);

namespace ExtendedTypeSystem\Type;

use ExtendedTypeSystem\php;

/** @psalm-check-type-exact $_true = true */
$_true = extractType(php::true);
