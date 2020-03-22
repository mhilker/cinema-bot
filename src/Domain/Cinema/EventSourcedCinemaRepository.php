<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\EventsArray;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Application\EventStore\StorableEventsArray;

final class EventSourcedCinemaRepository implements CinemaRepository
{
    private EventStore $eventStore;
    private EventPublisher $eventPublisher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher)
    {
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
    }

    public function load(CinemaID $id, callable $callable): CinemaUseCase
    {
        $storableEvents = $this->eventStore->load($id);
        $events = EventsArray::from($storableEvents);

        return $callable($events);
    }

    public function save(CinemaUseCase $cinema): void
    {
        $events = $cinema->extractEvents();

        $storableEvents = StorableEventsArray::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventPublisher->publish($events);
    }
}
