<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Group\GroupID;

final class FoundGroupCommand implements Command
{
    private GroupID $groupID;
    private ChatID $chatID;

    public function __construct(GroupID $groupID, ChatID $chatID)
    {
        $this->groupID = $groupID;
        $this->chatID = $chatID;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
    }

    public function getChatID(): ChatID
    {
        return $this->chatID;
    }
}
