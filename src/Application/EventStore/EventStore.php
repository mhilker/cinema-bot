<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use CinemaBot\Application\EventStream\EventStreamID;

interface EventStore
{
    public function load(EventStreamID $id): StorableEvents;

    public function save(StorableEvents $events): void;
}
