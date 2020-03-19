<?php

declare(strict_types=1);

namespace CinemaBot\Domain\RemoveTerm;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;

final class RemoveTermCommand implements Command
{
    private GroupID $groupID;
    private Term $term;

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
