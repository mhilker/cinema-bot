<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class Show
{
    private MovieName $name;
    private ShowTimes $times;

    private function __construct(MovieName $name, ShowTimes $times)
    {
        $this->name = $name;
        $this->times = $times;
    }

    public static function from(MovieName $name, ShowTimes $times): self
    {
        return new self($name, $times);
    }

    public function getName(): MovieName
    {
        return $this->name;
    }

    public function getTimes(): ShowTimes
    {
        return $this->times;
    }
}
