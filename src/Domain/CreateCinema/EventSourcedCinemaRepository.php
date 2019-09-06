<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\ES\EventStore;
use CinemaBot\Application\ES\StorableEvents;

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

    public function save(CreateCinemaUseCase $cinema): void
    {
        $events = $cinema->extractEvents();

        $storableEvents = StorableEvents::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventBus->publish($events);
    }
}
