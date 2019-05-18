<?php

declare(strict_types=1);

namespace CinemaBot;

use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Downloader
 */
class DownloaderTest extends TestCase
{
    public function testDownloadsFiles(): void
    {
        $url = 'https://example.com';

        $downloader = new Downloader();
        $fileName = $downloader->download($url);

        $this->assertFileExists($fileName);
        $this->assertStringStartsWith('/tmp/cinema-bot-', $fileName);
    }
}
