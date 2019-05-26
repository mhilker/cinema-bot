<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Application\ES\StorableEvents;
use CinemaBot\Application\ES\EventStore;

final class EventSourcedCinemaRepository implements CinemaRepository
{
    /** @var EventStore */
    private $eventStore;

    /** @var EventBus */
    private $eventBus;

    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    public function load(CinemaID $cinemaID): Cinema
    {
        $storableEvents = $this->eventStore->load($cinemaID);
        $events = Events::from($storableEvents);

        return new Cinema($events);
    }

    public function save(Cinema $cinema): void
    {
        $events = $cinema->extractEvents();

        $storableEvents = StorableEvents::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventBus->dispatch($events);
    }
}
