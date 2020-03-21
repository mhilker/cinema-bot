<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Cinema\CinemaID;

final class CrawlCinemaCommand implements Command
{
    private CinemaID $id;

    public function __construct(CinemaID $id)
    {
        $this->id = $id;
    }

    public function getID(): CinemaID
    {
        return $this->id;
    }
}
