<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\Command\CrawlCinemaCLICommand;
use CinemaBot\Application\Command\CreateCinemaCLICommand;
use CinemaBot\Application\Command\FoundGroupCLICommand;
use CinemaBot\Application\Command\SendNotificationCLICommand;
use CinemaBot\Application\Command\WebHookAction;
use CinemaBot\Application\CQRS\DirectCommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Application\ES\PDOEventStore;
use CinemaBot\Domain\CinemaList\CinemaListProjector;
use CinemaBot\Domain\CinemaList\PDOCinemaListProjection;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommand;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommandHandler;
use CinemaBot\Domain\AddShowToCinema\EventSourcedCinemaRepository as EventSourcedCinemaRepositoryAddShowToCinema;
use CinemaBot\Domain\AddShowToCinema\Parser\Crawler;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommand;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommandHandler;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjector;
use CinemaBot\Domain\ChatIDToGroupIDMap\MySQLChatGroupProjection;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommandHandler;
use CinemaBot\Domain\CreateCinema\EventSourcedCinemaRepository as EventSourcedCinemaRepositoryCreateCinema;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;
use CinemaBot\Domain\FoundGroup\FoundGroupCommand;
use CinemaBot\Domain\FoundGroup\FoundGroupCommandHandler;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommandHandler;
use CinemaBot\Domain\SendNotifications\MarkdownNotificationFormatter;
use CinemaBot\Domain\SendNotifications\NotifierSystem;
use CinemaBot\Domain\SendNotifications\TelegramNotifier;
use CinemaBot\Domain\Watchlist\PDOWatchlistProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjector;
use CinemaBot\Infrastructure\Telegram\TelegramToken;
use mysqli;
use PDO;
use Slim\App;
use Symfony\Component\Console\Application;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

final class Container
{
    /** @var DirectEventBus */
    private $eventBus;

    public function getCLIApp(): Application
    {
        $commandBus = $this->getCommandBus();
        $eventBus = $this->getEventBus();
        $pdo = $this->getPDO();

        $botApi     = new BotApi(TelegramToken::get());
        $formatter  = new MarkdownNotificationFormatter();
        $telegram   = new TelegramNotifier($botApi, $formatter);
        $projection = new PDOWatchlistProjection($pdo);

        $notifierSystem = new NotifierSystem($projection, $telegram);

        $app = new Application();
        $app->add(new CrawlCinemaCLICommand($commandBus, $eventBus, new PDOCinemaListProjection($pdo)));
        $app->add(new CreateCinemaCLICommand($commandBus, $eventBus));
        $app->add(new SendNotificationCLICommand($notifierSystem));
        $app->add(new FoundGroupCLICommand($commandBus, $eventBus));

        return $app;
    }

    public function getWebApp(): App
    {
        $pdo   = $this->getPDO();
        $mysql = $this->getMySQL();

        $watchlistProjection = new PDOWatchlistProjection($pdo);
        $chatGroupProjection = new MySQLChatGroupProjection($mysql);

        $commandBus = $this->getCommandBus();
        $eventBus   = $this->getEventBus();

        $bot = new Client(TelegramToken::get());
        $bot->on()

        $config = [
            'settings' => [
                'displayErrorDetails' => getenv('DISPLAY_ERRORS') === 'true',
            ],
        ];

        $app = new App($config);
        $app->post('/webhook/telegram', new WebHookAction($bot));
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
        $repository2 = new EventSourcedCinemaRepositoryCreateCinema($eventStore, $eventBus);
        $repository1 = new EventSourcedCinemaRepositoryAddShowToCinema($eventStore, $eventBus);

        $crawler = new Crawler();

        $commandBus = new DirectCommandBus();
        $commandBus->add(CreateCinemaCommand::class, new CreateCinemaCommandHandler($repository2));
        $commandBus->add(CrawlCinemaCommand::class, new CrawlCinemaCommandHandler($repository1, $crawler));
        $commandBus->add(AddTermToWatchlistCommand::class, new AddTermToWatchlistCommandHandler($eventBus));
        $commandBus->add(RemoveFromWatchlistCommand::class, new RemoveFromWatchlistCommandHandler($eventBus));
        $commandBus->add(FoundGroupCommand::class, new FoundGroupCommandHandler($eventBus));

        return $commandBus;
    }

    public function getEventBus(): DirectEventBus
    {
        if ($this->eventBus !== null) {
            return $this->eventBus;
        }

        $pdo = $this->getPDO();
        $mysql = $this->getMySQL();

        $watchlistProjection = new PDOWatchlistProjection($pdo);
        $cinemaListProjection = new PDOCinemaListProjection($pdo);
        $chatGroupProjection = new MySQLChatGroupProjection($mysql);

        $eventBus = new DirectEventBus();
        $eventBus->add(new WatchlistProjector($watchlistProjection));
        $eventBus->add(new CinemaListProjector($cinemaListProjection));
        $eventBus->add(new ChatGroupProjector($chatGroupProjection));

        return $this->eventBus = $eventBus;
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

    private function getMySQL(): mysqli
    {
        return new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            $this->getDBPassword(),
            getenv('DB_NAME'),
        );
    }
}
