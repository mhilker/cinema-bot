<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

interface Notifier
{
    public function send(Movie $movie, string $chatId): void;
}