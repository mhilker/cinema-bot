<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CommandHandler;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Command\RemoveFromWatchlistCommand;
use CinemaBot\Domain\Event\TermRemovedEvent;

class RemoveFromWatchlistCommandHandler implements CommandHandler
{
    /** @var EventBus */
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(RemoveFromWatchlistCommand $command): void
    {
        $events = Events::from([
            new TermRemovedEvent($command->getTerm()),
        ]);

        $this->eventBus->dispatch($events);
    }
}
