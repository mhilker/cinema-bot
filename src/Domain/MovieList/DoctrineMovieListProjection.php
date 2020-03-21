<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Shows;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineMovieListProjection implements MovieListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): Shows
    {
        return Shows::from([]);
    }

    public function insert(MovieName $name): void
    {
        $sql = <<< SQL
        INSERT OR IGNORE INTO 
            "movie_list" ("movie_name")
        VALUES
            (:movie_name);
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'movie_name' => $name->asString(),
        ]);
    }
}
