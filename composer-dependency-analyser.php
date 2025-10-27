<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use Typhoon\Type\Generator\Generator;

return (new Configuration())
    ->ignoreUnknownClasses([Generator::class]);
