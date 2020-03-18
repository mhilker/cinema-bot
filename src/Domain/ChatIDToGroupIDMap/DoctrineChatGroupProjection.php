<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineChatGroupProjection implements ChatGroupProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(ChatID $chatID, GroupID $groupID): void
    {
        $sql = <<<SQL
        INSERT INTO `chat_id_to_group_id_map` (`chat_id`, `group_id`) 
        VALUES (:chat_id, :group_id);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'chat_id' => $chatID->asString(),
            'group_id' => $groupID->asString(),
        ]);
    }

    public function loadGroupIDByChatID(ChatID $chatID): GroupID
    {
        $sql = <<<SQL
        SELECT `group_id` 
        FROM `chat_id_to_group_id_map` 
        WHERE `chat_id` = :chat_id
        LIMIT 1;
        SQL;


        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'chat_id' => $chatID->asString(),
        ]);

        $row = $statement->fetch();
        return GroupID::from($row['group_id']);
    }
}
