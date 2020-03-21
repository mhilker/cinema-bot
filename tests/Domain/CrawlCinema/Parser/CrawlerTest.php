<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\CrawlCinema\Crawler\RealCrawler;
use CinemaBot\Domain\CrawlCinema\Downloader\Downloader;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use CinemaBot\Domain\URL;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\Crawler\RealCrawler
 */
final class CrawlerTest extends TestCase
{
    public function test(): void
    {
        $url = URL::from('https://example.com/');

        $content = file_get_contents(__DIR__ . '/_files/kinoprogramm.html');

        $downloader = $this->createMock(Downloader::class);
        $downloader->expects($this->exactly(16))
            ->method('download')
            ->willReturn($content);

        $parser = $this->createMock(Parser::class);
        $parser->expects($this->exactly(15))
            ->method('parse')
            ->with($content)->willReturn(Shows::from([
                Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            ]));

        $crawler = new RealCrawler($downloader, $parser);
        $actual = $crawler->crawl($url);

        $expected = Shows::from([
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
            Show::from(MovieName::from('Test'), ShowTimes::from([ShowTime::fromString('2020-01-01T00:00:00Z')])),
        ]);

        $this->assertEquals($expected, $actual);
    }
}
