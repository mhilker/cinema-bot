<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use CinemaBot\Application\EventStream\EventStreamID;

interface EventStore
{
    /**
     * @throws EventStoreException
     */
    public function load(EventStreamID $id): StorableEvents;

    /**
     * @throws EventStoreException
     */
    public function save(StorableEvents $events): void;

    /**
     * @throws EventStoreException
     */
    public function loadAll(): StorableEvents;
}
