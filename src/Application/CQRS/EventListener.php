<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface EventListener
{
    public function dispatch(Events $events): void;
}
