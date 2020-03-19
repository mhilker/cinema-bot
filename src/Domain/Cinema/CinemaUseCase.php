<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Application\CQRS\Events;

interface CinemaUseCase
{
    public function extractEvents(): Events;
}
