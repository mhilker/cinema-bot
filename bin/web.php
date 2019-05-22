#!/usr/bin/env php
<?php

use CinemaBot\Application\Action\WebHookAction;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Domain\Command\AddToWatchlistCommand;
use CinemaBot\Domain\Command\RemoveFromWatchlistCommand;
use CinemaBot\Domain\CommandHandler\AddToWatchlistCommandHandler;
use CinemaBot\Domain\CommandHandler\RemoveFromWatchlistCommandHandler;
use CinemaBot\Domain\Watchlist\PDOWatchlistProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjector;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$dsn = 'mysql:host='. getenv('DB_HOST') .';dbname=' . getenv('DB_NAME');
$username = getenv('DB_USER');
$password = file_get_contents(getenv('DB_PASSWORD_FILE'));
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
];

$pdo = new PDO($dsn, $username, $password, $options);

$projection = new PDOWatchlistProjection($pdo);

$eventBus = new DirectEventBus();
$eventBus->add(new WatchlistProjector($projection));

$commandBus = new DirectCommandBus();
$commandBus->add(AddToWatchlistCommand::class, new AddToWatchlistCommandHandler($eventBus));
$commandBus->add(RemoveFromWatchlistCommand::class, new RemoveFromWatchlistCommandHandler($eventBus));

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);
$app->get('/', new WebHookAction($commandBus, $projection));
$app->run();
