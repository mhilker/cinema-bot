<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

interface Parser
{
    public function parse(string $fileName): Movies;
}
