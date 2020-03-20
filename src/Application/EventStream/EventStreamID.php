<?php

declare(strict_types=1);

namespace CinemaBot\Application\EventStream;

interface EventStreamID
{
    public function asString(): string;
}
