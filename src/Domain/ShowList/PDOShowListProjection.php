<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use PDO;

final class PDOShowListProjection implements ShowListProjection
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function load(): Movies
    {
//        $sql = <<< SQL
//        SELECT
//            `cinema_id`
//        FROM
//            `cinema_list`;
//        SQL;
//
//        $statement = $this->pdo->query($sql);
//
//        $list = [];
//
//        while (($row = $statement->fetch()) !== false) {
//            $list[] = CinemaID::from($row['cinema_id']);
//        }

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

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'cinema_id'  => $cinemaID->asString(),
            'movie_name' => $movieName->asString(),
            'movie_time' => $movieTime->asString(),
        ]);
    }
}
