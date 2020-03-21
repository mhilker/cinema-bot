<?php

declare(strict_types=1);

namespace CinemaBot\Domain\GroupList;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Group\GroupIDs;
use CinemaBot\Domain\GroupName;
use CinemaBot\Domain\GroupNames;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineGroupListProjection implements GroupListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): GroupIDs
    {
        $sql = <<< SQL
        SELECT "group_id" FROM "group_list"; 
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([]);

        $ids = [];
        while ($row = $statement->fetch()) {
            $ids[] = GroupID::from($row['group_id']);
        }

        return GroupIDs::from($ids);
    }

    public function add(GroupID $id): void
    {
        $sql = <<< SQL
        INSERT OR IGNORE INTO 
            "group_list" ("group_id")
        VALUES
            (:group_id);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'group_id' => $id->asString(),
        ]);
    }
}
