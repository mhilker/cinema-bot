<?php

declare(strict_types=1);

namespace CinemaBot\Domain\ShowList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;

interface ShowListProjection
{
    public function load(): Movies;

    public function insert(CinemaID $cinemaID, MovieName $movieName, MovieTime $movieTime): void;
}
