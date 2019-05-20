<?php

use CinemaBot\Application\Action\WebHookAction;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);
$app->get('/', new WebHookAction());
$app->run();
