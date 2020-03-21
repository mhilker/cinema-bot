<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Crawler;

use CinemaBot\Domain\Shows;
use CinemaBot\Domain\URL;

interface Crawler
{
    public function crawl(URL $url): Shows;
}
