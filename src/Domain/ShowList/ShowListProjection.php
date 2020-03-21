<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;

interface ShowListProjection
{
    public function loadUpcomingShows(): Shows;

    public function insert(CinemaID $id, MovieName $name, ShowTime $time): void;
}
