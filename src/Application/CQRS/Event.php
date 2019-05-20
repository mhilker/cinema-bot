<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface Event
{
    public function getTopic(): string;
}
