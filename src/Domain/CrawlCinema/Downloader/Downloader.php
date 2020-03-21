<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Downloader;

use CinemaBot\Domain\URL;

interface Downloader
{
    /**
     * @throws DownloadException
     */
    public function download(URL $url): string;
}
