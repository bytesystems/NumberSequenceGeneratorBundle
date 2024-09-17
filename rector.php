<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php81: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withSets(
        [SymfonySetList::SYMFONY_71,
            SymfonySetList::SYMFONY_CODE_QUALITY,
            SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION]
    )
    ->withTypeCoverageLevel(0);
