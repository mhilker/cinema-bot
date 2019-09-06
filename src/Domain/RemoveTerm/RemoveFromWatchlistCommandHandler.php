<?php

declare(strict_types=1);

namespace CinemaBot\Domain\RemoveTerm;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\TermRemovedEvent;

final class RemoveFromWatchlistCommandHandler implements CommandHandler
{
    /** @var EventDispatcher */
    private $eventPublisher;

    public function __construct(EventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function handle(RemoveFromWatchlistCommand $command): void
    {
        $events = Events::from([
            new TermRemovedEvent($command->getTerm()),
        ]);

        $this->eventPublisher->publish($events);
    }
}
