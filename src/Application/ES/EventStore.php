<?php

declare(strict_types=1);

namespace CinemaBot\Application\ES;

use CinemaBot\Application\Aggregate\AggregateID;

interface EventStore
{
    public function load(AggregateID $id): StorableEvents;

    public function save(StorableEvents $events): void;
}
