<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\ShowAddedEvent;

final class MovieListProjector implements EventListener
{
    private MovieListProjection $projection;

    public function __construct(MovieListProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof ShowAddedEvent) {
            $this->handleCinemaCreatedEvent($event);
        }
    }

    private function handleCinemaCreatedEvent(ShowAddedEvent $event): void
    {
        $this->projection->insert($event->getMovieName(), $event->getMovieTime());
    }
}
