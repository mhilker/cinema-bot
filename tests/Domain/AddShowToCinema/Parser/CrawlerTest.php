<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\CrawlCinema\Downloader\Downloader;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use CinemaBot\Domain\URL;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\Parser\Crawler
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
            ->with($content)->willReturn(Movies::from([
                Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            ]));

        $crawler = new Crawler($downloader, $parser);
        $actual = $crawler->crawl($url);

        $expected = Movies::from([
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
            Movie::from(MovieName::from('Test'), MovieTimes::from([MovieTime::fromString('2020-01-01T00:00:00Z')])),
        ]);

        $this->assertEquals($expected, $actual);
    }
}
