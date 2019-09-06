<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Downloader;

use CinemaBot\Domain\URL;

final class CopyDownloader implements Downloader
{
    public function download(URL $url): string
    {
        return file_get_contents($url->asString());
    }
}
