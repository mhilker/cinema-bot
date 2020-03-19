<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;

final class WatchlistProjector implements EventListener
{
    private WatchlistProjection $projection;

    public function __construct(WatchlistProjection $projection)
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
