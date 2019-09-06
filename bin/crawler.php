<?php

declare(strict_types=1);

use CinemaBot\Infrastructure\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$app = $container->getCLIApp();
$app->setAutoExit(false);
$app->run();
sleep(300);
