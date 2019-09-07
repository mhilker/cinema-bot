<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;
use PDO;

final class PDOWatchlistProjection implements WatchlistProjection
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function loadByGroupID(GroupID $groupID): Terms
    {
        $sql = 'SELECT * FROM `watchlist` WHERE `group_id` = :group_id;';

        $statement = $this->pdo->query($sql);

        $terms = [];

        while (($row = $statement->fetch()) !== false) {
            $terms[] = Term::from($row['term']);
        }

        return Terms::from($terms);
    }

    public function add(GroupID $groupID, Term $term): void
    {
        $sql = 'INSERT INTO `watchlist` (`group_id`, `term`) VALUES (:group_id, :term);';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'group_id' => $groupID->asString(),
            'term'     => $term->asString(),
        ]);
    }

    public function remove(GroupID $groupID, Term $term): void
    {
        $sql = 'DELETE FROM `watchlist` WHERE `group_id` = :group_id AND `term` = :term;';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'group_id' => $groupID->asString(),
            'term'     => $term->asString(),
        ]);
    }
}
