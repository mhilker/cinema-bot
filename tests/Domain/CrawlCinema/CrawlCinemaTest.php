<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema;

use CinemaBot\Application\CQRS\EventsArray;
use CinemaBot\Domain\Cinema\CinemaID;
use CinemaBot\Domain\CrawlCinema\Crawler\StubCrawler;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use CinemaBot\Domain\URL;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\CrawlCinemaUseCase
 */
final class CrawlCinemaTest extends TestCase
{
    public function testCrawlsCinema(): void
    {
        $id = CinemaID::from('123');
        $url = URL::from('https://example.com/');

        $events = EventsArray::from([
            new CinemaCreatedEvent($id, $url),
        ]);

        $crawler = new StubCrawler(Shows::from([
            Show::from(MovieName::from('Test 1'), ShowTimes::from([ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
            Show::from(MovieName::from('Test 2'), ShowTimes::from([ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
            Show::from(MovieName::from('Test 3'), ShowTimes::from([ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))])),
        ]));

        $cinema = new CrawlCinemaUseCase($events, $crawler);
        $cinema->crawl();

        $expectedEvents = EventsArray::from([
            new ShowAddedEvent($id, MovieName::from('Test 1'), ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 2'), ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
            new ShowAddedEvent($id, MovieName::from('Test 3'), ShowTime::from(new DateTimeImmutable('2019-05-01T12:15:45+02:00'))),
        ]);

        $this->assertEquals($expectedEvents, $cinema->extractEvents());
    }
}
