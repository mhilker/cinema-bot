<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

interface CinemaRepository
{
    public function save(CreateCinemaUseCase $cinema): void;
}
