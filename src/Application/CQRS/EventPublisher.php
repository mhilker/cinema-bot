<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface EventPublisher
{
    public function publish(Events $events): void;
}
