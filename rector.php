<?php

declare(strict_types=1);

return Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withParallel()
    ->withCache(__DIR__ . '/var/rector')
    ->withPhpSets()
    ->withSkip([
        Rector\Php55\Rector\String_\StringClassNameToClassConstantRector::class,
        Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector::class,
        Rector\Php80\Rector\Class_\StringableForToStringRector::class,
    ]);
