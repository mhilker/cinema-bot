<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

use SplQueue;

final class DirectEventBus implements EventPublisher, EventDispatcher
{
    /** @var EventListener[] */
    private array $eventListeners = [];

    private SplQueue $events;

    public function __construct()
    {
        $this->events = new SplQueue();
    }

    public function publish(Events $events): void
    {
        $this->events->enqueue($events);
    }

    public function dispatch(): void
    {
        while (!$this->events->isEmpty()) {
            $events = $this->events->dequeue();
            foreach ($events as $event) {
                foreach ($this->eventListeners as $eventListener) {
                    $eventListener->handle($event);
                }
            }
        }
    }

    public function add(EventListener $eventListener): void
    {
        $this->eventListeners[] = $eventListener;
    }
}
