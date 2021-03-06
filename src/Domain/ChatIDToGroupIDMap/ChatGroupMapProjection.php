<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Group\GroupID;

interface ChatGroupMapProjection
{
    public function add(ChatID $chatID, GroupID $groupID): void;

    public function loadGroupIDByChatID(ChatID $id): GroupID;

    public function loadChatIDByGroupID(GroupID $id): ChatID;
}
