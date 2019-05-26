<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\Parser\Crawler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\Parser\Crawler
 */
class CrawlerTest extends TestCase
{
    public function test(): void
    {
        $crawler = new Crawler();
        $movies = $crawler->crawl();

        // TODO
        $this->assertTrue(true);
    }
}
