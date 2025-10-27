<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    ->ignoreErrorsOnPackage('symfony/polyfill-php84', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreUnknownClasses(['Typhoon\Type\Generator\Generator']);
