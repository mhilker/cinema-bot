<?php

declare(strict_types=1);

namespace CinemaBot\Domain\WatchList;

use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineWatchListProjection implements WatchListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadByGroupID(GroupID $id): Terms
    {
        $sql = <<<SQL
        SELECT * 
        FROM "watchlist" 
        WHERE "group_id" = :group_id;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'group_id' => $id->asString(),
        ]);

        $terms = [];

        while ($row = $statement->fetch()) {
            $terms[] = Term::from($row['term']);
        }

        return Terms::from($terms);
    }

    public function add(GroupID $id, Term $term): void
    {
        $sql = <<<SQL
        INSERT OR IGNORE INTO "watchlist" ("group_id", "term") 
        VALUES (:group_id, :term);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'group_id' => $id->asString(),
            'term'     => $term->asString(),
        ]);
    }

    public function remove(GroupID $id, Term $term): void
    {
        $sql = <<<SQL
        DELETE FROM "watchlist"
        WHERE "group_id" = :group_id 
          AND "term" = :term;
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'group_id' => $id->asString(),
            'term'     => $term->asString(),
        ]);
    }
}
