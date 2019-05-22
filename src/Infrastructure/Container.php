<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\Action\WebHookAction;
use CinemaBot\Application\Command\CrawlSiteCLICommand;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Domain\Command\AddToWatchlistCommand;
use CinemaBot\Domain\Command\CrawlSiteCommand;
use CinemaBot\Domain\Command\RemoveFromWatchlistCommand;
use CinemaBot\Domain\CommandHandler\AddToWatchlistCommandHandler;
use CinemaBot\Domain\CommandHandler\CrawlSiteCommandHandler;
use CinemaBot\Domain\CommandHandler\RemoveFromWatchlistCommandHandler;
use CinemaBot\Domain\Crawler\Crawler;
use CinemaBot\Domain\Notifier\MovieNotifier;
use CinemaBot\Domain\Notifier\TelegramNotifier;
use CinemaBot\Domain\Repository\EventSourcedCalendarRepository;
use CinemaBot\Domain\Watchlist\PDOWatchlistProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjector;
use PDO;
use Slim\App;
use Symfony\Component\Console\Application;
use TelegramBot\Api\BotApi;

class Container
{
    public function getCLIApp(): Application
    {
        $commandBus = $this->getCommandBus();

        $app = new Application();
        $app->add(new CrawlSiteCLICommand($commandBus));
        return $app;
    }

    public function getWebApp(): App
    {
        $pdo        = $this->getPDO();
        $projection = new PDOWatchlistProjection($pdo);
        $commandBus = $this->getCommandBus();

        $app = new App([
            'settings' => [
                'displayErrorDetails' => true,
            ],
        ]);
        $app->get('/', new WebHookAction($commandBus, $projection));
        return $app;
    }

    public function getCommandBus(): DirectCommandBus
    {
        $eventBus   = $this->getEventBus();
        $repository = new EventSourcedCalendarRepository();
        $crawler    = new Crawler();

        $commandBus = new DirectCommandBus();
        $commandBus->add(CrawlSiteCommand::class, new CrawlSiteCommandHandler($eventBus, $repository, $crawler));
        $commandBus->add(AddToWatchlistCommand::class, new AddToWatchlistCommandHandler($eventBus));
        $commandBus->add(RemoveFromWatchlistCommand::class, new RemoveFromWatchlistCommandHandler($eventBus));
        return $commandBus;
    }

    public function getEventBus(): DirectEventBus
    {
        $pdo        = $this->getPDO();
        $projection = new PDOWatchlistProjection($pdo);
        $token      = file_get_contents(getenv('TELEGRAM_TOKEN_FILE'));
        $botApi     = new BotApi($token);
        $telegram   = new TelegramNotifier($botApi);

        $eventBus = new DirectEventBus();
        $eventBus->add(new WatchlistProjector($projection));
        $eventBus->add(new MovieNotifier($telegram));

        return $eventBus;
    }

    public function getPDO(): PDO
    {
        $dsn      = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = file_get_contents(getenv('DB_PASSWORD_FILE'));

        return new PDO($dsn, $username, $password, [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
