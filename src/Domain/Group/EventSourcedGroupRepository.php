<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Group;

use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\EventsArray;
use CinemaBot\Application\EventStore\EventStore;
use CinemaBot\Application\EventStore\StorableEventsArray;

final class EventSourcedGroupRepository implements GroupRepository
{
    private EventStore $eventStore;
    private EventPublisher $eventPublisher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher)
    {
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
    }

    public function load(GroupID $id, callable $callable): GroupUseCase
    {
        $storableEvents = $this->eventStore->load($id);
        $events = EventsArray::from($storableEvents);

        return $callable($events);
    }

    public function save(GroupUseCase $group): void
    {
        $events = $group->extractEvents();

        $storableEvents = StorableEventsArray::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventPublisher->publish($events);
    }
}
