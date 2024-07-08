<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
//use Rector\Core\Configuration\Option;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * @see \Rector\Config\RectorConfig::symfonyContainerXml()
 */
return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->paths([__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::PHP_83,
        LevelSetList::UP_TO_PHP_83,
    ]);

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_71,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    $rectorConfig->sets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);

    $rectorConfig->sets([
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ]);

    $rectorConfig->importShortClasses(false);

};
