<?php

declare(strict_types=1);

namespace CinemaBot\Application\Aggregate;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\Events;

abstract class AbstractAggregate
{
    /** @var Event[] */
    private $events = [];

    public function __construct(?Events $events)
    {
        if ($events !== null) {
            foreach ($events as $event) {
                $this->apply($event);
            }
        }
    }

    protected function record(Event $event): void
    {
        $this->events[] = $event;
        $this->apply($event);
    }

    abstract protected function apply(Event $event): void;

    public function extractEvents(): Events
    {
        $events = $this->events;
        $this->events = [];
        return Events::from($events);
    }
}
