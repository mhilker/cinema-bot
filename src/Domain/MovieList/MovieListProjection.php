<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Shows;

interface MovieListProjection
{
    public function load(): Shows;

    public function insert(MovieName $name): void;
}
