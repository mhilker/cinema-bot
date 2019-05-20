<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

class DirectCommandBus implements CommandBus
{
    /** @var CommandHandler[] */
    private $commandHandlers = [];

    public function dispatch(Command $command): void
    {
        $this->commandHandlers[get_class($command)]->handle($command);
    }

    public function add(string $commandName, CommandHandler $commandHandler): void
    {
        $this->commandHandlers[$commandName] = $commandHandler;
    }
}
