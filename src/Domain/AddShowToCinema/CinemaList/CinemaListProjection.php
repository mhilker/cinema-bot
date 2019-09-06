<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\CinemaList;

use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CinemaIDs;

interface CinemaListProjection
{
    public function load(): CinemaIDs;

    public function insert(CinemaID $id): void;
}
