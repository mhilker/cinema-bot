<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Application\ES\EventStore;
use CinemaBot\Application\ES\PDOEventStore;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;
use Doctrine\DBAL\Driver\Connection;
use Psr\Container\ContainerInterface;

final class EventStoreFactory
{
    public function __invoke(ContainerInterface $container): EventStore
    {
        $connection = $container->get(Connection::class);

        return  new PDOEventStore($connection, [
            CinemaCreatedEvent::TOPIC => CinemaCreatedEvent::class,
            ShowAddedEvent::TOPIC     => ShowAddedEvent::class,
            TermAddedEvent::TOPIC     => TermAddedEvent::class,
            TermRemovedEvent::TOPIC   => TermRemovedEvent::class,
        ]);
    }
}
