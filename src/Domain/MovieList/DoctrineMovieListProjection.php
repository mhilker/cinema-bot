<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineMovieListProjection implements MovieListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): Movies
    {
        // TODO
        return Movies::from([]);
    }

    public function insert(MovieName $movieName, MovieTime $movieTime): void
    {
        $sql = <<< SQL
        INSERT INTO 
            "movie_list" ("movie_name", "movie_time")
        VALUES
            (:movie_name, :movie_time);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'movie_name' => $movieName->asString(),
            'movie_time' => $movieTime->asString(),
        ]);
    }
}
