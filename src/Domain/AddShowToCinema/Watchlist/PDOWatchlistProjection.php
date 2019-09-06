<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Watchlist;

use PDO;

final class PDOWatchlistProjection implements WatchlistProjection
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): Terms
    {
        $sql = 'SELECT * FROM `watchlist`;';

        $statement = $this->pdo->query($sql);

        $terms = [];

        while (($row = $statement->fetch()) !== false) {
            $terms[] = Term::from($row['term']);
        }

        return Terms::from($terms);
    }

    public function add(Term $term): void
    {
        $sql = 'INSERT INTO `watchlist` (`term`) VALUES (:term);';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'term' => $term->asString(),
        ]);
    }

    public function remove(Term $term): void
    {
        $sql = 'DELETE FROM `watchlist` WHERE term = :term;';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'term' => $term->asString(),
        ]);
    }
}
