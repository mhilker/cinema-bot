<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Downloader;

use CinemaBot\Domain\URL;
use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\Downloader\CopyDownloader
 */
final class DownloaderTest extends TestCase
{
    /**
     * @large
     */
    public function testDownloadsFiles(): void
    {
        $url = URL::from('https://example.com');

        $downloader = new CopyDownloader();
        $content = $downloader->download($url);

        $expected = new DOMDocument();
        $expected->loadHTMLFile(__DIR__ . '/_files/expected-download-response.html');
        $expectedXpath = new DOMXPath($expected);

        $actual = new DOMDocument();
        $actual->loadHTML($content);
        $actualXpath = new DOMXPath($actual);

        $this->assertEquals(
            $expectedXpath->evaluate('/html/body/div/h1'),
            $actualXpath->evaluate('/html/body/div/h1'),
        );
    }
}
