<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase;

interface CinemaRepository
{
    public function save(AddShowToCinemaUseCase $cinema): void;

    public function load(CinemaID $cinemaID): AddShowToCinemaUseCase;
}
