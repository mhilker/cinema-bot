<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

class DirectEventBus implements EventBus
{
    /** @var EventListener[] */
    private $eventListeners = [];

    public function dispatch(Events $events): void
    {
        foreach ($this->eventListeners as $eventListener) {
            $eventListener->dispatch($events);
        }
    }

    public function add(EventListener $eventListener): void
    {
        $this->eventListeners[] = $eventListener;
    }
}
