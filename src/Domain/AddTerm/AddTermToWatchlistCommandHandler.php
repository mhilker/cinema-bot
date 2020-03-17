<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermAddedEvent;

final class AddTermToWatchlistCommandHandler implements CommandHandler
{
    private EventPublisher $eventPublisher;

    public function __construct(EventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function handle(AddTermToWatchlistCommand $command): void
    {
        $events = Events::from([
            new TermAddedEvent($command->getGroupID(), $command->getTerm()),
        ]);

        $this->eventPublisher->publish($events);
    }
}
