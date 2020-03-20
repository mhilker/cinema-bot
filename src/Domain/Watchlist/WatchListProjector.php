<?php

declare(strict_types=1);

namespace CinemaBot\Domain\WatchList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;

final class WatchListProjector implements EventListener
{
    private WatchListProjection $projection;

    public function __construct(WatchListProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Event $event): void
    {
        if ($event instanceof TermAddedEvent) {
            $this->handleTermAddedEvent($event);
        }
        if ($event instanceof TermRemovedEvent) {
            $this->handleTermRemovedEvent($event);
        }
    }

    private function handleTermAddedEvent(TermAddedEvent $event): void
    {
        $this->projection->add($event->getGroupID(), $event->getTerm());
    }

    private function handleTermRemovedEvent(TermRemovedEvent $event): void
    {
        $this->projection->remove($event->getGroupID(), $event->getTerm());
    }
}
