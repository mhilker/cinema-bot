<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\ShowAddedEvent;

final class ShowListProjector implements EventListener
{
    private ShowListProjection $projection;

    public function __construct(ShowListProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Events $events): void
    {
        foreach ($events as $event) {
            $this->handleEvent($event);
        }
    }

    private function handleEvent(Event $event): void
    {
        if ($event instanceof ShowAddedEvent) {
            $this->handleCinemaCreatedEvent($event);
        }
    }

    private function handleCinemaCreatedEvent(ShowAddedEvent $event): void
    {
        $this->projection->insert($event->getCinemaID(), $event->getMovieName(), $event->getMovieTime());
    }
}
