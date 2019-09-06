<?php

declare(strict_types=1);

namespace CinemaBot\Domain\FoundGroup;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;

final class FoundGroupCommand implements Command
{
    /** @var GroupID */
    private $groupID;

    /** @var ChatID */
    private $chatID;

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
