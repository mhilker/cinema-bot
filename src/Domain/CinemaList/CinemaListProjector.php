<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\CinemaCreatedEvent;

final class CinemaListProjector implements EventListener
{
    /** @var CinemaListProjection */
    private $projection;

    public function __construct(CinemaListProjection $projection)
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
        if ($event instanceof CinemaCreatedEvent) {
            $this->handleCinemaCreatedEvent($event);
        }
    }

    private function handleCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->projection->insert($event->getID());
    }
}
