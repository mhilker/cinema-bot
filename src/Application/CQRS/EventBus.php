<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface EventBus
{
    public function publish(Events $events): void;

    public function dispatch(): void;
}
