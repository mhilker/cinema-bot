<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;

interface ChatGroupProjection
{
    public function add(ChatID $chatID, GroupID $groupID): void;

    public function loadGroupIDByChatID(ChatID $chatID): GroupID;
}
