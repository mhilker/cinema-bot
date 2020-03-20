<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\Movies;

interface Parser
{
    public function parse(string $html): Movies;
}
