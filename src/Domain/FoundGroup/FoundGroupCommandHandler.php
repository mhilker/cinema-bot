<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\GroupFoundedEvent;

final class FoundGroupCommandHandler implements CommandHandler
{
    private EventPublisher $eventPublisher;

    public function __construct(EventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function handle(FoundGroupCommand $command): void
    {
        $events = Events::from([
            new GroupFoundedEvent($command->getGroupID(), $command->getChatID()),
        ]);

        $this->eventPublisher->publish($events);
    }
}
