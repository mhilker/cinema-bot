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
            $this->handleShowAddedEvent($event);
        }
    }

    private function handleShowAddedEvent(ShowAddedEvent $event): void
    {
        $this->projection->add($event->getName());
    }
}
