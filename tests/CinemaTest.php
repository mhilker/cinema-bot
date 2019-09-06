<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\URL;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\AddShowToCinema\AddShowToCinemaUseCase
 */
class CinemaTest extends TestCase
{
    public function testAddsShowsToCalendar(): void
    {
        $id = CinemaID::from('123');
        $url = URL::from('https://example.com/');

        $calendar = AddShowToCinemaUseCase::createNew($id, $url);
        $calendar->addShow(MovieName::from('Test 1'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));
        $calendar->addShow(MovieName::from('Test 2'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));
        $calendar->addShow(MovieName::from('Test 3'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00')));

        $expectedEvents = Events::from([
            new CinemaCreatedEvent($id, $url),
            new ShowAddedEvent($id, MovieName::from('Test 1'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 2'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 3'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
        ]);

        $this->assertEquals($expectedEvents, $calendar->extractEvents());
    }
}
