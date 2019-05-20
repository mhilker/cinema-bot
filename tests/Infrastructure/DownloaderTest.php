<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Infrastructure\CopyDownloader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Infrastructure\CopyDownloader
 */
class DownloaderTest extends TestCase
{
    public function testDownloadsFiles(): void
    {
        $url = 'https://example.com';

        $downloader = new CopyDownloader();
        $fileName = $downloader->download($url);

        $this->assertFileExists($fileName);
        $this->assertStringStartsWith('/tmp/cinema-bot-', $fileName);
    }
}
