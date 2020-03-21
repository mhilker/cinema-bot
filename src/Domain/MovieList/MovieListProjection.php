<?php

declare(strict_types=1);

namespace CinemaBot\Domain\MovieList;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieNames;

interface MovieListProjection
{
    public function load(): MovieNames;

    public function add(MovieName $name): void;
}
