<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStore;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStream\EventStreamID;

interface StorableEvent extends Event
{
    public static function fromJSON(string $json): StorableEvent;

    public function getEventStreamID(): EventStreamID;

    public function asJSON(): string;
}
