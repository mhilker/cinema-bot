<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

use ArrayIterator;
use Traversable;

final class EventsArray implements Events
{
    /** @var Event[] */
    private array $events = [];

    private function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public static function from(iterable $events): self
    {
        return new self($events);
    }

    private function add(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return Traversable | Event[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
