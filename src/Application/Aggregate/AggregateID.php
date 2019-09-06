<?php

declare(strict_types=1);

namespace CinemaBot\Application\Aggregate;

interface AggregateID
{
    public function asString(): string;
}
