<?php

declare(strict_types=1);

namespace CinemaBot\Application\ES;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class StorableEvents implements IteratorAggregate
{
    /** @var StorableEvent[] */
    private $events = [];

    public function __construct(iterable $events)
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public static function from(iterable $events): StorableEvents
    {
        return new self($events);
    }

    public function add(StorableEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return Traversable<StorableEvent>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }
}
