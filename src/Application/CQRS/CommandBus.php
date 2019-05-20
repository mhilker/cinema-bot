<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
