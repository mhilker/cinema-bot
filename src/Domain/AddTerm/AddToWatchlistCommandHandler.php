<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\AddTerm\AddToWatchlistCommand;
use CinemaBot\Domain\Event\TermAddedEvent;

final class AddToWatchlistCommandHandler implements CommandHandler
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
