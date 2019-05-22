<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Downloader;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\URL;

class CopyDownloader implements Downloader
{
    public function download(URL $url): ExistingFile
    {
        $fileName = tempnam('/tmp/', 'cinema-bot-');

        copy($url->asString(), $fileName);

        return ExistingFile::from($fileName);
    }
}
