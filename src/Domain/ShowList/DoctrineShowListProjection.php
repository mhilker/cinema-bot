<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineShowListProjection implements ShowListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): Movies
    {
        return Movies::from([]);
    }

    public function insert(CinemaID $cinemaID, MovieName $movieName, MovieTime $movieTime): void
    {
        $sql = <<< SQL
        INSERT INTO 
            `movie_list` (`cinema_id`, `movie_name`, `movie_time`)
        VALUES
            (:cinema_id, :movie_name, :movie_time);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'cinema_id'  => $cinemaID->asString(),
            'movie_name' => $movieName->asString(),
            'movie_time' => $movieTime->asString(),
        ]);
    }
}
