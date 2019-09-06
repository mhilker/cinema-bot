<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Parser;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\Movies;

interface Parser
{
    public function parse(ExistingFile $fileName): Movies;
}
