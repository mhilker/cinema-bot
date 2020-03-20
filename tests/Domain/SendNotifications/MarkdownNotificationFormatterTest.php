<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\SendNotifications\MarkdownNotificationFormatter
 */
final class MarkdownNotificationFormatterTest extends TestCase
{
    public function testFormatsMovieToMarkdown(): void
    {
        $movie = Movie::from(MovieName::from('The Movie Name'), MovieTimes::from([
            MovieTime::fromString('2019-01-01T17:30:00Z'),
            MovieTime::fromString('2019-01-02T20:15:00Z'),
            MovieTime::fromString('2019-01-03T22:00:00Z'),
        ]));

        $formatter = new MarkdownNotificationFormatter();
        $actual = $formatter->format($movie);

        $expected = <<< MARKDOWN
        The Movie Name
        *Dienstag (01.01.2019)*:
        `18:30`
        *Mittwoch (02.01.2019)*:
        `21:15`
        *Donnerstag (03.01.2019)*:
        `23:00`
        
        MARKDOWN;
        $this->assertEquals($expected, $actual);
    }
}
