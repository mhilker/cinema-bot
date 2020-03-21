<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieNames;
use Doctrine\DBAL\Driver\Connection;

final class DoctrineMovieListProjection implements MovieListProjection
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(): MovieNames
    {
        $sql = <<< SQL
        SELECT "movie_name" FROM "movie_list"; 
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([]);

        $names = [];
        while ($row = $statement->fetch()) {
            $names[] = MovieName::from($row['movie_name']);
        }

        return MovieNames::from($names);
    }

    public function add(MovieName $name): void
    {
        $sql = <<< SQL
        INSERT INTO 
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
