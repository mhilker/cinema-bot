<?php

declare(strict_types=1);

use CinemaBot\Application\Command\HomeAction;
use CinemaBot\Application\Command\WebHookAction;
use CinemaBot\Infrastructure\ContainerConfig;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(new ContainerConfig());
$container = $builder->build();

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->get('/', $container->get(HomeAction::class));
$app->post('/webhook/telegram', $container->get(WebHookAction::class));
$app->run();
