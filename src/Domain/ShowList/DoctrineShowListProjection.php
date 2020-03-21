<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineShowListProjection implements ShowListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): Shows
    {
//        $sql = <<< SQL
//        SELECT * FROM "show_list";
//        SQL;
//
//        $statement = $this->connection->query($sql);
//
//        $shows = [];
//        while ($row = $statement->fetch()) {
//            $shows[] = Show::from(MovieName::from($row[]), ShowTimes::from());
//        }

        return Shows::from([]);
    }

    public function insert(CinemaID $id, MovieName $name, ShowTime $time): void
    {
        $sql = <<< SQL
        INSERT INTO 
            "show_list" ("cinema_id", "movie_name", "show_time")
        VALUES
            (:cinema_id, :movie_name, :show_time);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'cinema_id'  => $id->asString(),
            'movie_name' => $name->asString(),
            'show_time'  => $time->asString(),
        ]);
    }
}
