<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Crawler;

use CinemaBot\Domain\Shows;
use CinemaBot\Domain\URL;

final class StubCrawler implements Crawler
{
    private Shows $shows;

    public function __construct(Shows $shows)
    {
        $this->shows = $shows;
    }

    public function crawl(URL $url): Shows
    {
        return $this->shows;
    }
}
