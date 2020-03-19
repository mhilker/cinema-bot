<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Cinema\CinemaUseCase;

interface CinemaRepository
{
    public function save(CinemaUseCase $cinema): void;

    public function load(CinemaID $cinemaID, callable $callable): CinemaUseCase;
}
