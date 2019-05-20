<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;

class WatchlistProjector implements EventListener
{
    /** @var WatchlistProjection */
    private $projection;

    public function __construct(WatchlistProjection $projection)
    {
        $this->projection = $projection;
    }

    public function dispatch(Events $events): void
    {
        foreach ($events as $event) {
            $this->dispatchEvent($event);
        }
    }

    private function dispatchEvent(Event $event): void
    {
        switch ($event->getTopic()) {
            case TermAddedEvent::TOPIC:
                /** @var TermAddedEvent $event */
                $this->dispatchTermAddedEvent($event);
                break;
            case TermRemovedEvent::TOPIC:
                /** @var TermRemovedEvent $event */
                $this->dispatchTermRemovedEvent($event);
                break;
        }
    }

    private function dispatchTermAddedEvent(TermAddedEvent $event): void
    {
        $this->projection->add($event->getTerm());
    }

    private function dispatchTermRemovedEvent(TermRemovedEvent $event): void
    {
        $this->projection->remove($event->getTerm());
    }
}
