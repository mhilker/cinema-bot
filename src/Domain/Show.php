<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use DateTimeImmutable;

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

    public static function fromArray(array $show): self
    {
        return new self(
            MovieName::from($show['name']),
            ShowTimes::fromArray($show['times']),
        );
    }

    public function asArray(): array
    {
        return [
            'name' => $this->name->asString(),
            'times' => $this->times->asArray(),
        ];
    }

    public function isAfter(DateTimeImmutable $date): bool
    {
        foreach ($this->times as $time) {
            if ($time->isAfter($date)) {
                return true;
            }
        }
        return false;
    }
}
