<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

use SplQueue;

final class DirectEventBus implements EventBus
{
    /** @var EventListener[] */
    private $eventListeners = [];

    /** @var SplQueue */
    private $events;

    public function publish(Events $events): void
    {
        $this->events->enqueue($events);
    }

    public function dispatch(): void
    {
        while (!$this->events->isEmpty()) {
            $events = $this->events->dequeue();
            foreach ($this->eventListeners as $eventListener) {
                $eventListener->handle($events);
            }
        }
    }

    public function add(EventListener $eventListener): void
    {
        $this->eventListeners[] = $eventListener;
    }
}
