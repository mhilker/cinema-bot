<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema;

use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use CinemaBot\Domain\URL;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\CrawlCinemaUseCase
 */
final class CrawlCinemaTest extends TestCase
{
    public function testAddsShowsToCalendar(): void
    {
        $id = CinemaID::from('123');
        $url = URL::from('https://example.com/');

        $calendar = new CrawlCinemaUseCase(Events::from([
            new CinemaCreatedEvent($id, $url),
        ]));
        $calendar->addShows(Movies::from([
            Movie::from(MovieName::from('Test 1'), MovieTimes::from([MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
            Movie::from(MovieName::from('Test 2'), MovieTimes::from([MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
            Movie::from(MovieName::from('Test 3'), MovieTimes::from([MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
        ]));

        $expectedEvents = Events::from([
            new ShowAddedEvent($id, MovieName::from('Test 1'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 2'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 3'), MovieTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
        ]);

        $this->assertEquals($expectedEvents, $calendar->extractEvents());
    }
}
