<?php

declare(strict_types=1);

namespace CinemaBot\Domain\TermList;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Term;

final class RemoveTermCommand implements Command
{
    private GroupID $id;
    private Term $term;

    public function __construct(GroupID $id, Term $term)
    {
        $this->id = $id;
        $this->term = $term;
    }

    public function getId(): GroupID
    {
        return $this->id;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }
}
