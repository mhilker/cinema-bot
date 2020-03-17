<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Application\EventStore\StorableEvents;

final class EventSourcedCinemaRepository implements CinemaRepository
{
    private EventStore $eventStore;
    private EventPublisher $eventPublisher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher)
    {
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
    }

    public function save(CreateCinemaUseCase $cinema): void
    {
        $events = $cinema->extractEvents();

        $storableEvents = StorableEvents::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventPublisher->publish($events);
    }
}
