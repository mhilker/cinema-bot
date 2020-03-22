<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use IteratorAggregate;
use Traversable;

interface StorableEvents extends IteratorAggregate
{
    /**
     * @return Traversable | StorableEvent[]
     */
    public function getIterator(): Traversable;
}
