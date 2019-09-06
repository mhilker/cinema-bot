<?php

declare(strict_types=1);

namespace CinemaBot\Domain\RemoveTerm;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;

final class RemoveFromWatchlistCommand implements Command
{
    /** @var GroupID */
    private $groupID;

    /** @var Term */
    private $term;

    public function __construct(GroupID $groupID, Term $term)
    {
        $this->groupID = $groupID;
        $this->term = $term;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }
}
