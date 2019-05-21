<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifier;

use CinemaBot\Domain\Movie;

interface Notifier
{
    public function send(Movie $movie, string $chatId): void;
}