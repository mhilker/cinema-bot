<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\CrawlCinema\Downloader\Downloader;
use CinemaBot\Domain\Movies;
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
            ->with($content)->willReturn(Movies::from([]));

        $crawler = new Crawler($downloader, $parser);
        $movies = $crawler->crawl($url);

        $this->assertCount(0, $movies);
    }
}
