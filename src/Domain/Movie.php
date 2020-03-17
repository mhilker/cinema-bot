<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class Movie
{
    private MovieName $name;
    private MovieTimes $times;

    private function __construct(MovieName $name, MovieTimes $times)
    {
        $this->name = $name;
        $this->times = $times;
    }

    public static function from(MovieName $name, MovieTimes $times): self
    {
        return new self($name, $times);
    }

    public function getName(): MovieName
    {
        return $this->name;
    }

    public function getTimes(): MovieTimes
    {
        return $this->times;
    }
}
