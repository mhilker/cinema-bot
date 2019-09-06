<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\Action\WebHookAction;
use CinemaBot\Application\Command\CrawlCinemaSymfonyCommand;
use CinemaBot\Application\Command\CreateCinemaSymfonyCommand;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Application\ES\PDOEventStore;
use CinemaBot\Domain\CinemaList\CinemaListProjector;
use CinemaBot\Domain\CinemaList\PDOCinemaListProjection;
use CinemaBot\Domain\AddTerm\AddToWatchlistCommand;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommand;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\AddTerm\AddToWatchlistCommandHandler;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommandHandler;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommandHandler;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommandHandler;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;
use CinemaBot\Domain\Parser\Crawler;
use CinemaBot\Domain\Notifier\NotifierSystem;
use CinemaBot\Domain\Notifier\TelegramNotifier;
use CinemaBot\Domain\EventSourcedCinemaRepository;
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

        $pdo = $this->getPDO();

        $app = new Application();
        $app->add(new CrawlCinemaSymfonyCommand($commandBus, new PDOCinemaListProjection($pdo)));
        $app->add(new CreateCinemaSymfonyCommand($commandBus));

        return $app;
    }

    public function getWebApp(): App
    {
        $pdo        = $this->getPDO();
        $projection = new PDOWatchlistProjection($pdo);
        $commandBus = $this->getCommandBus();

        $config = [
            'settings' => [
                'displayErrorDetails' => getenv('DISPLAY_ERRORS') === 'true',
            ],
        ];

        $app = new App($config);
        $app->post('/webhook/telegram', new WebHookAction($commandBus, $projection));
        return $app;
    }

    public function getCommandBus(): DirectCommandBus
    {
        $pdo = $this->getPDO();

        $eventStore = new PDOEventStore($pdo, [
            CinemaCreatedEvent::TOPIC => CinemaCreatedEvent::class,
            ShowAddedEvent::TOPIC     => ShowAddedEvent::class,
            TermAddedEvent::TOPIC     => TermAddedEvent::class,
            TermRemovedEvent::TOPIC   => TermRemovedEvent::class,
        ]);

        $eventBus = $this->getEventBus();
        $repository = new EventSourcedCinemaRepository($eventStore, $eventBus);

        $crawler = new Crawler();

        $commandBus = new DirectCommandBus();
        $commandBus->add(CreateCinemaCommand::class, new CreateCinemaCommandHandler($repository));
        $commandBus->add(CrawlCinemaCommand::class, new CrawlCinemaCommandHandler($repository, $crawler));
        $commandBus->add(AddToWatchlistCommand::class, new AddToWatchlistCommandHandler($eventBus));
        $commandBus->add(RemoveFromWatchlistCommand::class, new RemoveFromWatchlistCommandHandler($eventBus));

        return $commandBus;
    }

    public function getEventBus(): DirectEventBus
    {
        $pdo = $this->getPDO();

        $watchlistProjection = new PDOWatchlistProjection($pdo);

        $token      = $this->getTelegramToken();
        $botApi     = new BotApi($token);
        $telegram   = new TelegramNotifier($botApi);

        $cinemaListProjection = new PDOCinemaListProjection($pdo);

        $pdo = $this->getPDO();

        $eventStore = new PDOEventStore($pdo, [
            CinemaCreatedEvent::TOPIC => CinemaCreatedEvent::class,
            ShowAddedEvent::TOPIC     => ShowAddedEvent::class,
            TermAddedEvent::TOPIC     => TermAddedEvent::class,
            TermRemovedEvent::TOPIC   => TermRemovedEvent::class,
        ]);

        $eventBus = new DirectEventBus();

        $repository = new EventSourcedCinemaRepository($eventStore, $eventBus);

        $eventBus->add(new WatchlistProjector($watchlistProjection));
        $eventBus->add(new NotifierSystem($watchlistProjection, $telegram, $repository));
        $eventBus->add(new CinemaListProjector($cinemaListProjection));

        return $eventBus;
    }

    public function getPDO(): PDO
    {
        $dsn      = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = $this->getDBPassword();

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;',
        ]);
    }

    public function getTelegramToken(): string
    {
        $file = getenv('TELEGRAM_TOKEN_FILE');

        if ($file === false) {
            $token = getenv('TELEGRAM_TOKEN');
        } else {
            $token = file_get_contents($file);
        }

        return $token;
    }

    private function getDBPassword(): string
    {
        $file = getenv('DB_PASSWORD_FILE');

        if ($file === false) {
            $token = getenv('DB_PASSWORD');
        } else {
            $token = file_get_contents($file);
        }

        return $token;
    }
}
