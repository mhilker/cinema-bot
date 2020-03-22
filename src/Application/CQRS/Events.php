<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

use IteratorAggregate;
use Traversable;

interface Events extends IteratorAggregate
{
    /**
     * @return Traversable | Event[]
     */
    public function getIterator(): Traversable;
}
