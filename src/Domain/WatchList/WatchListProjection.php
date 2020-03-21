<?php

declare(strict_types=1);

namespace CinemaBot\Domain\WatchList;

use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;

interface WatchListProjection
{
    public function loadByGroupID(GroupID $id): Terms;

    public function add(GroupID $id, Term $value): void;

    public function remove(GroupID $id, Term $value): void;
}
