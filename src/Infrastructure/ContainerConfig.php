<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\DirectEventBus;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjection;
use CinemaBot\Domain\ChatIDToGroupIDMap\DoctrineChatGroupProjection;
use CinemaBot\Domain\CinemaList\CinemaListProjection;
use CinemaBot\Domain\CinemaList\DoctrineCinemaListProjection;
use CinemaBot\Domain\CreateCinema\CinemaRepository;
use CinemaBot\Domain\CreateCinema\EventSourcedCinemaRepository;
use CinemaBot\Domain\Watchlist\DoctrineWatchlistProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use CinemaBot\Infrastructure\Doctrine\DoctrineConnectionFactory;
use DI\Definition\Source\DefinitionArray;
use Doctrine\DBAL\Driver\Connection;
use TelegramBot\Api\Client;
use function DI\factory;
use function DI\get;

final class ContainerConfig extends DefinitionArray
{
    public function __construct()
    {
        parent::__construct([
            'db' => require __DIR__ . '/../../doctrine.php',

            Connection::class     => factory(DoctrineConnectionFactory::class),
            CommandBus::class     => factory(CommandBusFactory::class),
            DirectEventBus::class => factory(EventBusFactory::class),
            EventPublisher::class => get(DirectEventBus::class),
            EventStore::class     => factory(EventStoreFactory::class),

            WatchlistProjection::class  => get(DoctrineWatchlistProjection::class),
            CinemaListProjection::class => get(DoctrineCinemaListProjection::class),
            ChatGroupProjection::class  => get(DoctrineChatGroupProjection::class),

            CinemaRepository::class => get(EventSourcedCinemaRepository::class),

            Client::class => factory(TelegramFactory::class),
        ]);
    }
}
