<?php

declare(strict_types=1);

namespace CinemaBot\Domain\RemoveTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermRemovedEvent;

final class RemoveFromWatchlistCommandHandler implements CommandHandler
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
