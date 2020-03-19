<?php

declare(strict_types=1);

use CinemaBot\Application\Command\CrawlCinemaCLICommand;
use CinemaBot\Application\Command\CreateCinemaCLICommand;
use CinemaBot\Application\Command\FoundGroupCLICommand;
use CinemaBot\Application\Command\SendNotificationCLICommand;
use CinemaBot\Infrastructure\ContainerConfig;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(new ContainerConfig());
$container = $builder->build();

$app = new Application();
$app->add($container->get(CrawlCinemaCLICommand::class));
$app->add($container->get(SendNotificationCLICommand::class));
$app->run();
