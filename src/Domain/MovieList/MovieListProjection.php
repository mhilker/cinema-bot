<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;

interface MovieListProjection
{
    public function load(): Movies;

    public function insert(MovieName $movieName, MovieTime $movieTime): void;
}
