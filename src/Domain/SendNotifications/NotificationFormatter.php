<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\Movie;

interface NotificationFormatter
{
    public function format(Movie $movie): string;
}
