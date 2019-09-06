<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Downloader;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\URL;

interface Downloader
{
    public function download(URL $url): ExistingFile;
}
