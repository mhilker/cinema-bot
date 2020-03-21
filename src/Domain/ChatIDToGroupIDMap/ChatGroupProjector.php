<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\GroupFoundedEvent;

final class ChatGroupProjector implements EventListener
{
    private ChatGroupMapProjection $projection;

    public function __construct(ChatGroupMapProjection $projection)
    {
        $this->projection = $projection;
    }

    public function handle(Event $event): void
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
