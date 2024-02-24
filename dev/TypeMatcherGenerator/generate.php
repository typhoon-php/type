<?php

declare(strict_types=1);

namespace Typhoon\Type\TypeMatcherGenerator;

require_once __DIR__ . '/../../vendor/autoload.php';

$types = (new TypeVisitorParser())->parse();
$code = (new TypeMatcherGenerator())->generate($types);

file_put_contents(__DIR__ . '/../../src/TypeMatcher.php', $code);
