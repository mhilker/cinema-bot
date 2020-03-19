<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\CinemaCreatedEvent;

final class CinemaListProjector implements EventListener
{
    private CinemaListProjection $projection;

    public function __construct(CinemaListProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof CinemaCreatedEvent) {
            $this->handleCinemaCreatedEvent($event);
        }
    }

    private function handleCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->projection->insert($event->getCinemaID(), $event->getCinemaURL());
    }
}
