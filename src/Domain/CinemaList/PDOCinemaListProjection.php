<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CinemaIDs;
use PDO;

final class PDOCinemaListProjection implements CinemaListProjection
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function load(): CinemaIDs
    {
        $sql = <<< SQL
        SELECT
            `cinema_id`
        FROM
            `cinema_list`;
        SQL;

        $statement = $this->pdo->query($sql);

        $list = CinemaIDs::from([]);

        while (($row = $statement->fetch()) !== false) {
            $list->add(CinemaID::from($row['cinema_id']));
        }

        return $list;
    }

    public function insert(CinemaID $id): void
    {
        $sql = <<< SQL
        INSERT INTO 
            `cinema_list` (`cinema_id`)
        VALUES
            (:cinema_id);
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'cinema_id' => $id->asString(),
        ]);
    }
}
