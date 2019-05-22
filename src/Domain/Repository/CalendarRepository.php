<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Domain\Aggregate\Calendar;
use CinemaBot\Domain\Aggregate\CalendarID;

interface CalendarRepository
{
    public function save(Calendar $calendar): void;

    public function load(CalendarID $calendarID): Calendar;
}
