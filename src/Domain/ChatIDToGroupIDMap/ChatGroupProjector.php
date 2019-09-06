<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\GroupFoundedEvent;

final class ChatGroupProjector implements EventListener
{
    /** @var ChatGroupProjection */
    private $projection;

    public function __construct(ChatGroupProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Events $events): void
    {
        foreach ($events as $event) {
            $this->handleEvent($event);
        }
    }

    private function handleEvent(Event $event): void
    {
        if ($event instanceof GroupFoundedEvent) {
            $this->handleGroupFoundedEvent($event);
        }
    }

    private function handleGroupFoundedEvent(GroupFoundedEvent $event): void
    {
        $this->projection->add($event->getChatID(), $event->getGroupID());
    }
}
