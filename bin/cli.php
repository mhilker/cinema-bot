<?php

declare(strict_types=1);

use CinemaBot\Application\Command\CrawlSiteCLICommand;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Domain\Command\CrawlSiteCommand;
use CinemaBot\Domain\CommandHandler\CrawlSiteCommandHandler;
use CinemaBot\Domain\Notifier\MovieNotifier;
use CinemaBot\Domain\Notifier\TelegramNotifier;
use CinemaBot\Domain\Watchlist\PDOWatchlistProjection;
use Symfony\Component\Console\Application;
use TelegramBot\Api\BotApi;

require __DIR__ . '/../vendor/autoload.php';

$dsn = 'mysql:host='. getenv('DB_HOST') .';dbname=' . getenv('DB_NAME');
$username = getenv('DB_USER');
$password = file_get_contents(getenv('DB_PASSWORD_FILE'));
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
];

$pdo = new PDO($dsn, $username, $password, $options);

$token = file_get_contents(getenv('TELEGRAM_TOKEN_FILE'));
$botApi = new BotApi($token);
$telegram = new TelegramNotifier($botApi);

$eventBus = new DirectEventBus();
$eventBus->add(new MovieNotifier($telegram));

$projection = new PDOWatchlistProjection($pdo);

$commandBus = new DirectCommandBus();
$commandBus->add(CrawlSiteCommand::class, new CrawlSiteCommandHandler($eventBus, $projection));

$app = new Application();
$app->add(new CrawlSiteCLICommand($commandBus));
$app->run();
