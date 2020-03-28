<?php

declare(strict_types=1);

use CinemaBot\Application\Command\CreateCinemaAction;
use CinemaBot\Application\Command\CreateGroupAction;
use CinemaBot\Application\Command\HomeAction;
use CinemaBot\Application\Command\TelegramWebHookAction;
use CinemaBot\Infrastructure\ContainerConfig;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

(static function () {
    require __DIR__ . '/../vendor/autoload.php';

    $builder = new ContainerBuilder();
    $builder->addDefinitions(new ContainerConfig());
    $container = $builder->build();

    $app = AppFactory::create();
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware(true, true, true);
    $app->get('/', $container->get(HomeAction::class));
    $app->post('/webhook/telegram', $container->get(TelegramWebHookAction::class));
    $app->put('/cinema', $container->get(CreateCinemaAction::class));
    $app->put('/group', $container->get(CreateGroupAction::class));
    $app->run();
})();
