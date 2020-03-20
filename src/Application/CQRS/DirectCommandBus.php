<?php

declare(strict_types=1);

namespace CinemaBot\Application\CQRS;

final class DirectCommandBus implements CommandBus
{
    /** @var CommandHandler[] */
    private array $commandHandlers = [];

    private EventDispatcher $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(Command $command): void
    {
        $commandName = get_class($command);

        if (isset($this->commandHandlers[$commandName]) === false) {
            throw new CommandException(sprintf(
                'CommandHandler for Command "%s" not found',
                $commandName
            ));
        }

        $this->commandHandlers[$commandName]->handle($command);

        $this->eventDispatcher->dispatch();
    }

    public function add(string $commandName, CommandHandler $commandHandler): void
    {
        if (method_exists($commandHandler, 'handle') === false) {
            throw new CommandException(sprintf(
                'CommandHandler "%s" must provide "handle" method.',
                get_class($commandHandler)
            ));
        }

        $this->commandHandlers[$commandName] = $commandHandler;
    }
}
