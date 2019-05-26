<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\Cinema\CinemaIDs;

interface CinemaListProjection
{
    public function load(): CinemaIDs;

    public function insert(CinemaID $id): void;
}
