<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;

final class WatchlistProjector implements EventListener
{
    /** @var WatchlistProjection */
    private $projection;

    public function __construct(WatchlistProjection $projection)
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
        switch ($event->getTopic()) {
            case TermAddedEvent::TOPIC:
                /** @var TermAddedEvent $event */
                $this->handleTermAddedEvent($event);
                break;
            case TermRemovedEvent::TOPIC:
                /** @var TermRemovedEvent $event */
                $this->handleTermRemovedEvent($event);
                break;
        }
    }

    private function handleTermAddedEvent(TermAddedEvent $event): void
    {
        $this->projection->add($event->getTerm());
    }

    private function handleTermRemovedEvent(TermRemovedEvent $event): void
    {
        $this->projection->remove($event->getTerm());
    }
}
