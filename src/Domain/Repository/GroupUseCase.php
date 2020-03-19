<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Application\CQRS\Events;

interface GroupUseCase
{
    public function extractEvents(): Events;
}
