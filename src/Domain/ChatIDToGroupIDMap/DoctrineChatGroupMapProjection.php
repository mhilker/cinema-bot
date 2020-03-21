<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ChatIDToGroupIDMap;

use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\Group\GroupID;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineChatGroupMapProjection implements ChatGroupMapProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(ChatID $chatID, GroupID $groupID): void
    {
        $sql = <<<SQL
        INSERT OR IGNORE INTO "chat_id_to_group_id_map" ("chat_id", "group_id") 
        VALUES (:chat_id, :group_id);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'chat_id' => $chatID->asString(),
            'group_id' => $groupID->asString(),
        ]);
    }

    public function loadGroupIDByChatID(ChatID $id): GroupID
    {
        $sql = <<<SQL
        SELECT "group_id" 
        FROM "chat_id_to_group_id_map" 
        WHERE "chat_id" = :chat_id
        LIMIT 1;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'chat_id' => $id->asString(),
        ]);

        $row = $statement->fetch();
        return GroupID::from($row['group_id']);
    }

    public function loadChatIDByGroupID(GroupID $id): ChatID
    {
        $sql = <<<SQL
        SELECT "chat_id" 
        FROM "chat_id_to_group_id_map" 
        WHERE "group_id" = :group_id
        LIMIT 1;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'group_id' => $id->asString(),
        ]);

        $row = $statement->fetch();
        return GroupID::from($row['chat_id']);
    }
}
