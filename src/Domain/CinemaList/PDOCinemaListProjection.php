<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CinemaIDs;
use CinemaBot\Domain\URL;
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
        $sql = 'SELECT `cinema_id` FROM `cinema_list`;';

        $statement = $this->pdo->query($sql);

        $list = [];

        while (($row = $statement->fetch()) !== false) {
            $list[] = CinemaID::from($row['cinema_id']);
        }

        return CinemaIDs::from($list);
    }

    public function insert(CinemaID $id, URL $url): void
    {
        $sql = 'INSERT INTO `cinema_list` (`cinema_id`, `url`) VALUES (:cinema_id, :url);';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'cinema_id' => $id->asString(),
            'url'       => $url->asString(),
        ]);
    }
}
