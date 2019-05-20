<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface EventBus
{
    public function dispatch(Events $events): void;
}
