<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CinemaList;

use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\Cinema\CinemaIDs;
use CinemaBot\Domain\URL;

interface CinemaListProjection
{
    public function load(): CinemaIDs;

    public function insert(CinemaID $id, URL $url): void;
}
