<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Repository;

use CinemaBot\Domain\Aggregate\Calendar;
use CinemaBot\Domain\Aggregate\CalendarID;

class EventSourcedCalendarRepository implements CalendarRepository
{
    public function save(Calendar $calendar): void
    {
        // TODO: Implement save() method.
    }

    public function load(CalendarID $calendarID): Calendar
    {
        // TODO: Implement load() method.
    }
}
