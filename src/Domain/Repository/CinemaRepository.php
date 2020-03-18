<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase;
use CinemaBot\Domain\CinemaID;

interface CinemaRepository
{
    public function save(AddShowToCinemaUseCase $cinema): void;

    public function load(CinemaID $cinemaID): AddShowToCinemaUseCase;
}
