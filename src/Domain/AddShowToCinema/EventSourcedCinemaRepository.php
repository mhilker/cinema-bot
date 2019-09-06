<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Application\ES\EventStore;
use CinemaBot\Application\ES\StorableEvents;
use CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase;
use CinemaBot\Domain\AddShowToCinema\CinemaRepository;
use CinemaBot\Domain\CinemaID;

final class EventSourcedCinemaRepository implements CinemaRepository
{
    /** @var EventStore */
    private $eventStore;

    /** @var EventPublisher */
    private $eventPublisher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher)
    {
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
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

        $this->eventPublisher->publish($events);
    }
}
