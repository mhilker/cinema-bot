<?php

declare(strict_types=1);

namespace CinemaBot\Application\ES;

use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Application\CQRS\Event;

interface StorableEvent extends Event
{
    public static function fromJSON(string $json): StorableEvent;

    public function getAggregateID(): AggregateID;

    public function asJSON(): string;
}
