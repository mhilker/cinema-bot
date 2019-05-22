<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

interface Downloader
{
    public function download(string $url): string;
}
