<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;

interface WatchlistProjection
{
    public function getAll(): Terms;

    public function add(GroupID $groupID, Term $value): void;

    public function remove(GroupID $groupID, Term $value): void;
}
