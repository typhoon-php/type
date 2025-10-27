<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/generator',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withParallel()
    ->withCache(__DIR__ . '/var/rector')
    ->withPhpSets()
    ->withSkip([
        StringableForToStringRector::class,
        SensitiveConstantNameRector::class,
        // AddOverrideAttributeToOverriddenMethodsRector::class,
    ]);
