<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\ChatIDToGroupIDMap\DoctrineChatGroupMapProjection;
use CinemaBot\Domain\Cinema\CinemaRepository;
use CinemaBot\Domain\Cinema\EventSourcedCinemaRepository;
use CinemaBot\Domain\CinemaList\CinemaListProjection;
use CinemaBot\Domain\CinemaList\DoctrineCinemaListProjection;
use CinemaBot\Domain\CrawlCinema\Crawler\Crawler;
use CinemaBot\Domain\CrawlCinema\Crawler\RealCrawler;
use CinemaBot\Domain\CrawlCinema\Downloader\CopyDownloader;
use CinemaBot\Domain\CrawlCinema\Downloader\Downloader;
use CinemaBot\Domain\CrawlCinema\Parser\DOMParser;
use CinemaBot\Domain\CrawlCinema\Parser\Parser;
use CinemaBot\Domain\Group\EventSourcedGroupRepository;
use CinemaBot\Domain\Group\GroupRepository;
use CinemaBot\Domain\MovieList\DoctrineMovieListProjection;
use CinemaBot\Domain\MovieList\MovieListProjection;
use CinemaBot\Domain\SendNotifications\DoctrineNotificationProjection;
use CinemaBot\Domain\SendNotifications\NotificationProjection;
use CinemaBot\Domain\SendNotifications\Notifier;
use CinemaBot\Domain\SendNotifications\TelegramNotifier;
use CinemaBot\Domain\ShowList\DoctrineShowListProjection;
use CinemaBot\Domain\ShowList\ShowListProjection;
use CinemaBot\Domain\WatchList\DoctrineWatchListProjection;
use CinemaBot\Domain\WatchList\WatchListProjection;
use CinemaBot\Infrastructure\Doctrine\DoctrineConnectionFactory;
use CinemaBot\Infrastructure\Telegram\TelegramBotFactory;
use CinemaBot\Infrastructure\Telegram\TelegramClientFactory;
use DI\Definition\Source\DefinitionArray;
use Doctrine\DBAL\Driver\Connection;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use function DI\factory;
use function DI\get;

final class ContainerConfig extends DefinitionArray
{
    public function __construct()
    {
        parent::__construct([
            'db' => require __DIR__ . '/../../doctrine.php',

            Connection::class => factory(DoctrineConnectionFactory::class),
            CommandBus::class => factory(CommandBusFactory::class),
            DirectEventBus::class => factory(EventBusFactory::class),
            EventPublisher::class => get(DirectEventBus::class),
            EventDispatcher::class => get(DirectEventBus::class),
            EventStore::class => factory(EventStoreFactory::class),
            WatchListProjection::class  => get(DoctrineWatchListProjection::class),
            CinemaListProjection::class => get(DoctrineCinemaListProjection::class),
            ChatGroupMapProjection::class  => get(DoctrineChatGroupMapProjection::class),
            ShowListProjection::class => get(DoctrineShowListProjection::class),
            MovieListProjection::class => get(DoctrineMovieListProjection::class),
            CinemaRepository::class => get(EventSourcedCinemaRepository::class),
            GroupRepository::class => get(EventSourcedGroupRepository::class),
            Client::class => factory(TelegramClientFactory::class),
            BotApi::class => factory(TelegramBotFactory::class),
            Downloader::class => get(CopyDownloader::class),
            Crawler::class => get(RealCrawler::class),
            Parser::class => get(DOMParser::class),
            Notifier::class => get(TelegramNotifier::class),
            NotificationProjection::class => get(DoctrineNotificationProjection::class),
        ]);
    }
}
