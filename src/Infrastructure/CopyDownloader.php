<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Domain\Downloader;

class CopyDownloader implements Downloader
{
    public function download(string $url): string
    {
        $fileName = tempnam('/tmp/', 'cinema-bot-');
        copy($url, $fileName);
        return $fileName;
    }
}
