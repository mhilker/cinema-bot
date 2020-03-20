<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStream\AbstractEventStream;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Event\GroupFoundedEvent;
use CinemaBot\Domain\Group\GroupUseCase;
use CinemaBot\Domain\GroupID;

final class FoundGroupUseCase extends AbstractEventStream implements GroupUseCase
{
    private GroupID $groupID;
    private ChatID $chatID;

    public static function foundNew(GroupID $groupID, ChatID $chatID): FoundGroupUseCase
    {
        $Group = new self(null);
        $Group->record(new GroupFoundedEvent($groupID, $chatID));
        return $Group;
    }

    private function applyGroupFoundedEvent(GroupFoundedEvent $event): void
    {
        $this->groupID = $event->getGroupID();
        $this->chatID = $event->getChatID();
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof GroupFoundedEvent) {
            $this->applyGroupFoundedEvent($event);
        }
    }
}
