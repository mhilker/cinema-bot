<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Application\ES\EventStore;
use CinemaBot\Application\ES\StorableEvents;
use CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase;

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

    public function load(CinemaID $cinemaID): AddShowToCinemaUseCase
    {
        $storableEvents = $this->eventStore->load($cinemaID);
        $events = Events::from($storableEvents);

        return new AddShowToCinemaUseCase($events);
    }

    public function save(AddShowToCinemaUseCase $cinema): void
    {
        $events = $cinema->extractEvents();

        $storableEvents = StorableEvents::from($events);
        $this->eventStore->save($storableEvents);

        $this->eventBus->publish($events);
    }
}
