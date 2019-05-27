<?php

use CinemaBot\Infrastructure\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$app = $container->getWebApp();
$app->run();
