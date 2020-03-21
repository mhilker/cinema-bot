<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Domain\CinemaID;

interface CinemaRepository
{
    public function save(CinemaUseCase $cinema): void;

    public function load(CinemaID $id, callable $callable): CinemaUseCase;
}
