<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use DateTimeImmutable;

class MovieTime
{
    /** @var DateTimeImmutable */
    private $dateTime;

    public function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
