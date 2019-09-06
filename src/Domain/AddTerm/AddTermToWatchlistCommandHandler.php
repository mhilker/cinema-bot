<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermAddedEvent;

final class AddTermToWatchlistCommandHandler implements CommandHandler
{
    /** @var EventBus */
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(AddTermToWatchlistCommand $command): void
    {
        $events = Events::from([
            new TermAddedEvent($command->getTerm()),
        ]);

        $this->eventBus->publish($events);
    }
}
