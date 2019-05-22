<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Aggregate;

interface AggregateID
{
    public function asString(): string;
}
