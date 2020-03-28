<?php

declare(strict_types=1);

use CinemaBot\Application\Command\CrawlerCLICommand;
use CinemaBot\Application\Command\NotifierCLICommand;
use CinemaBot\Application\Command\ReplayEventsCLICommand;
use CinemaBot\Infrastructure\ContainerConfig;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

(static function () {
    require __DIR__ . '/../vendor/autoload.php';

    $builder = new ContainerBuilder();
    $builder->addDefinitions(new ContainerConfig());
    $container = $builder->build();

    $app = new Application();
    $app->add($container->get(ReplayEventsCLICommand::class));
    $app->add($container->get(CrawlerCLICommand::class));
    $app->add($container->get(NotifierCLICommand::class));
    $app->run();
})();
