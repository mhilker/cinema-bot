<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\Downloader\CopyDownloader;
use CinemaBot\Domain\URL;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\Downloader\CopyDownloader
 */
class DownloaderTest extends TestCase
{
    public function testDownloadsFiles(): void
    {
        $url = URL::from('https://example.com');

        $downloader = new CopyDownloader();
        $fileName = $downloader->download($url);

        $this->assertFileExists($fileName->asString());
        $this->assertStringStartsWith('/tmp/cinema-bot-', $fileName->asString());
    }
}
