<?php

declare(strict_types=1);

use CinemaBot\Application\Command\WebHookAction;
use CinemaBot\Infrastructure\CinemaBotConfig;
use DI\ContainerBuilder;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(new CinemaBotConfig());
$container = $builder->build();

$app = new App([
    'settings' => [
        'displayErrorDetails' => getenv('DISPLAY_ERRORS') === 'true',
    ],
]);
$app->post('/webhook/telegram', $container->get(WebHookAction::class));
$app->run();
