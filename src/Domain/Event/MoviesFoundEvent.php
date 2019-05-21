<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Movies;

final class MoviesFoundEvent implements Event
{
    public const TOPIC = 'cinema_bot.movies_found';

    /** @var Movies */
    private $movies;

    public function __construct(Movies $movies)
    {
        $this->movies = $movies;
    }

    public function getMovies(): Movies
    {
        return $this->movies;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }
}
