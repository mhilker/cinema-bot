<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface EventDispatcher
{
    public function dispatch(): void;
}
