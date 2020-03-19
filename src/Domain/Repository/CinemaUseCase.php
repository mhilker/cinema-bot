<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Application\CQRS\Events;

interface CinemaUseCase
{
    public function extractEvents(): Events;
}
