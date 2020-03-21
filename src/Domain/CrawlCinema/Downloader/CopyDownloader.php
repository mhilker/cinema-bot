<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Downloader;

use CinemaBot\Domain\URL;

final class CopyDownloader implements Downloader
{
    public function download(URL $url): string
    {
        $contents = @file_get_contents($url->asString());
        if ($contents === false) {
            throw new DownloadException('Could not download file from URL');
        }
        return $contents;
    }
}
