<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\AddShowToCinema\Parser\Crawler;
use CinemaBot\Domain\URL;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\AddShowToCinema\Parser\Crawler
 */
class CrawlerTest extends TestCase
{
    public function test(): void
    {
        $url = URL::from('https://example.com/');

        $crawler = new Crawler();
        $movies = $crawler->crawl($url);

        // TODO
        $this->assertTrue(true);
    }
}
