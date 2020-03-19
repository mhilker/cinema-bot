<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\URL;

final class CreateCinemaCommand implements Command
{
    private CinemaID $id;
    private URL $url;

    public function __construct(CinemaID $id, URL $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getCinemaID(): CinemaID
    {
        return $this->id;
    }

    public function getURL(): URL
    {
        return $this->url;
    }
}
