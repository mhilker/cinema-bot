<?php

declare(strict_types=1);

namespace CinemaBot\Domain\GroupList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\GroupFoundedEvent;

final class GroupListProjector implements EventListener
{
    private GroupListProjection $projection;

    public function __construct(GroupListProjection $projection)
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
        $this->projection->add($event->getGroupID());
    }
}
