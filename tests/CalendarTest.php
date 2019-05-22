<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Aggregate\Calendar;
use CinemaBot\Domain\Aggregate\CalendarID;
use CinemaBot\Domain\Event\CalendarCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\Aggregate\Calendar
 */
class CalendarTest extends TestCase
{
    public function testAddsShowsToCalendar(): void
    {
        $id = CalendarID::from('123');

        $calendar = Calendar::create($id);
        $calendar->addShow(MovieName::from('Test 1'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));
        $calendar->addShow(MovieName::from('Test 2'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));
        $calendar->addShow(MovieName::from('Test 3'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));

        $expectedEvents = Events::from([
            new CalendarCreatedEvent($id),
            new ShowAddedEvent($id, MovieName::from('Test 1'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 2'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 3'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
        ]);

        $this->assertEquals($expectedEvents, $calendar->extractEvents());
    }
}
