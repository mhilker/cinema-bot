<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Command;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Cinema\CinemaID;

final class CrawlCinemaCommand implements Command
{
    /** @var CinemaID */
    private $id;

    public function __construct(CinemaID $id)
    {
        $this->id = $id;
    }

    public function getId(): CinemaID
    {
        return $this->id;
    }
}
