<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CinemaIDs;
use CinemaBot\Domain\URL;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineCinemaListProjection implements CinemaListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): CinemaIDs
    {
        $sql = <<<SQL
        SELECT `cinema_id` 
        FROM `cinema_list`;
        SQL;

        $statement = $this->connection->query($sql);

        $list = [];

        while (($row = $statement->fetch()) !== false) {
            $list[] = CinemaID::from($row['cinema_id']);
        }

        return CinemaIDs::from($list);
    }

    public function insert(CinemaID $id, URL $url): void
    {
        $sql = <<<SQL
        INSERT INTO `cinema_list` (`cinema_id`, `url`) 
        VALUES (:cinema_id, :url);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'cinema_id' => $id->asString(),
            'url'       => $url->asString(),
        ]);
    }
}
