<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

class Movie
{
    /** @var string */
    private $name;

    /** @var MovieTimes */
    private $times;

    public function __construct(string $name, MovieTimes $times)
    {
        $this->name = $name;
        $this->times = $times;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimes(): MovieTimes
    {
        return $this->times;
    }
}
