<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Aggregate;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Aggregate\AggregateID;

abstract class AbstractAggregate
{
    /** @var Event[] */
    private $events = [];

    private function __construct(?Events $events)
    {
        if ($events !== null) {
            foreach ($events as $event) {
                $this->apply($event);
            }
        }
    }

    public static function fromEvents(Events $events): self
    {
        return new static($events);
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

    abstract public function getAggregateId(): AggregateID;
}
