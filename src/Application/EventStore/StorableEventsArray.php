<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use ArrayIterator;
use Traversable;

final class StorableEventsArray implements StorableEvents
{
    /** @var StorableEvent[] */
    private array $events = [];

    private function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public static function from(iterable $events): StorableEvents
    {
        return new self($events);
    }

    private function add(StorableEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return Traversable | StorableEvent[]
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
