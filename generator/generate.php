<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator;

use Typhoon\Type\Generator\Spec\Type;

require_once __DIR__ . '/Spec/Property.php';
require_once __DIR__ . '/Spec/Template.php';
require_once __DIR__ . '/Spec/Type.php';
require_once __DIR__ . '/Generator.php';
require_once __DIR__ . '/../vendor/autoload.php';

/** @var non-empty-list<Type> */
$types = require __DIR__ . '/spec.php';

$generator = new Generator(__DIR__ . '/../src/Type', $types);
$generator->cleanUp();
$generator->generateTypes();
$generator->generateVisitor();
$generator->generateReduced();
$generator->generateFallback();
