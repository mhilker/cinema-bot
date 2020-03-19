<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Domain\CinemaID;

interface CinemaRepository
{
    public function save(CinemaUseCase $cinema): void;

    public function load(CinemaID $cinemaID): CinemaUseCase;
}
