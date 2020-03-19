<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Domain\AddShowToCinema\Downloader\CopyDownloader;
use CinemaBot\Domain\AddShowToCinema\Downloader\Downloader;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjection;
use CinemaBot\Domain\ChatIDToGroupIDMap\DoctrineChatGroupProjection;
use CinemaBot\Domain\Cinema\CinemaRepository;
use CinemaBot\Domain\Cinema\EventSourcedCinemaRepository;
use CinemaBot\Domain\CinemaList\CinemaListProjection;
use CinemaBot\Domain\CinemaList\DoctrineCinemaListProjection;
use CinemaBot\Domain\Group\EventSourcedGroupRepository;
use CinemaBot\Domain\Group\GroupRepository;
use CinemaBot\Domain\SendNotifications\MarkdownNotificationFormatter;
use CinemaBot\Domain\SendNotifications\NotificationFormatter;
use CinemaBot\Domain\SendNotifications\NotifierSystem;
use CinemaBot\Domain\SendNotifications\TelegramNotifier;
use CinemaBot\Domain\ShowList\DoctrineShowListProjection;
use CinemaBot\Domain\ShowList\ShowListProjection;
use CinemaBot\Domain\Watchlist\DoctrineWatchlistProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
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

            Connection::class      => factory(DoctrineConnectionFactory::class),
            CommandBus::class      => factory(CommandBusFactory::class),
            DirectEventBus::class  => factory(EventBusFactory::class),
            EventPublisher::class  => get(DirectEventBus::class),
            EventDispatcher::class => get(DirectEventBus::class),
            EventStore::class      => factory(EventStoreFactory::class),

            WatchlistProjection::class  => get(DoctrineWatchlistProjection::class),
            CinemaListProjection::class => get(DoctrineCinemaListProjection::class),
            ChatGroupProjection::class  => get(DoctrineChatGroupProjection::class),
            ShowListProjection::class => get(DoctrineShowListProjection::class),
            CinemaRepository::class => get(EventSourcedCinemaRepository::class),
            GroupRepository::class => get(EventSourcedGroupRepository::class),
            Client::class => factory(TelegramClientFactory::class),
            BotApi::class => factory(TelegramBotFactory::class),
            Downloader::class => get(CopyDownloader::class),
            NotifierSystem::class => get(TelegramNotifier::class),
            NotificationFormatter::class => get(MarkdownNotificationFormatter::class),
        ]);
    }
}
