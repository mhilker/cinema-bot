<?php

declare(strict_types=1);

namespace CinemaBot\Domain\WatchList;

use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;

interface WatchListProjection
{
    public function loadByGroupID(GroupID $groupID): Terms;

    public function add(GroupID $groupID, Term $value): void;

    public function remove(GroupID $groupID, Term $value): void;
}
