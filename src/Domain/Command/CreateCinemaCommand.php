<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Command;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\URL;

final class CreateCinemaCommand implements Command
{
    /** @var URL */
    private $url;

    /** @var CinemaID */
    private $id;

    public function __construct(CinemaID $id, URL $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getID(): CinemaID
    {
        return $this->id;
    }

    public function getURL(): URL
    {
        return $this->url;
    }
}
