<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use mysqli;

final class MySQLChatGroupProjection implements ChatGroupProjection
{
    /** @var mysqli */
    private $mysql;

    public function __construct(mysqli $mysql)
    {
        $this->mysql = $mysql;
    }

    public function add(ChatID $chatID, GroupID $groupID): void
    {
        $sql = 'INSERT INTO `chat_id_to_group_id_map` (`chat_id`, `group_id`) VALUES (?, ?);';

        $statement = $this->mysql->prepare($sql);
        $statement->bind_param('ss', $chatID->asString(), $groupID->asString());
        $statement->execute();
        $statement->close();
    }

    public function loadGroupIDByChatID(ChatID $chatID): GroupID
    {
        $sql = 'SELECT `group_id` FROM `chat_id_to_group_id_map` WHERE `chat_id` = ? LIMIT 1;';

        $statement = $this->mysql->prepare($sql);
        $statement->bind_param('s', $chatID->asString());
        $statement->bind_result($groupID);
        $statement->execute();
        $statement->fetch();
        $statement->close();

        return GroupID::from($groupID);
    }
}
