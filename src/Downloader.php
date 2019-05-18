<?php

declare(strict_types=1);

namespace CinemaBot;

class Downloader
{
    public function download(string $url): string
    {
        $fileName = tempnam('/tmp/', 'cinema-bot-');
        copy($url, $fileName);
        return $fileName;
    }
}
