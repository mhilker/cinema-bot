<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use DateTimeZone;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineShowListProjection implements ShowListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadUpcomingShows(): Shows
    {
        $sql = <<< SQL
        SELECT * 
        FROM "show_list" 
        WHERE "show_time" > DATETIME(:now);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'now' => (new \DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
        ]);

        $rows = [];
        while ($row = $statement->fetch()) {
            $name = $row['movie_name'];
            $time = $row['show_time'];
            $rows[$name][] = $time;
        }

        $shows = [];
        foreach ($rows as $name => $times) {
            $shows[] = Show::fromArray([
                'name' => $name,
                'times' => $times,
            ]);
        }

        return Shows::from($shows);
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
            'show_time'  => $time->format('Y-m-d H:i:s'),
        ]);
    }
}
