<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CommandHandler;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Command\AddToWatchlistCommand;
use CinemaBot\Domain\Event\TermAddedEvent;

class AddToWatchlistCommandHandler implements CommandHandler
{
    /** @var EventBus */
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(AddToWatchlistCommand $command): void
    {
        $events = Events::from([
            new TermAddedEvent($command->getTerm()),
        ]);

        $this->eventBus->dispatch($events);
    }
}
