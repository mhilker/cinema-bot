<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\CinemaID;

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
