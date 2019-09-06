<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Movie;

interface Notifier
{
    public function send(Movie $movie, ChatID $chatId): void;
}
