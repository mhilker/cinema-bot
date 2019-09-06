<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

final class DirectCommandBus implements CommandBus
{
    /** @var CommandHandler[] */
    private $commandHandlers = [];

    public function dispatch(Command $command): void
    {
        $commandName = get_class($command);

        if (isset($this->commandHandlers[$commandName]) === false) {
            throw new CommandHandlerNotFoundException(sprintf(
                'CommandHandler for Command "%s" not found',
                $commandName
            ));
        }

        $this->commandHandlers[$commandName]->handle($command);
    }

    public function add(string $commandName, CommandHandler $commandHandler): void
    {
        $this->commandHandlers[$commandName] = $commandHandler;
    }
}
