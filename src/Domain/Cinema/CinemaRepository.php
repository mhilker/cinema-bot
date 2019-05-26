<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

interface CinemaRepository
{
    public function save(Cinema $cinema): void;

    public function load(CinemaID $cinemaID): Cinema;
}
