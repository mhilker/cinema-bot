<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\CrawlCinema\Parser\DOMParser
 */
final class ParserTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testExtractsTimesForMovieFromHtml(string $html, Shows $expected): void
    {
        $parser = new DOMParser();
        $actual = $parser->parse($html);

        $this->assertEquals($expected, $actual);
    }

    public function provider(): iterable
    {
        yield [
            'file' => file_get_contents(__DIR__ . '/_files/kinoprogramm.html'),
            'movies' => Shows::from([
                Show::from(
                    MovieName::from('Asbury Park: Riot, Redemption, Rock N Roll'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-22T20:15:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('Aladdin'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-22T20:00:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('John Wick: Kapitel 3'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-22T20:20:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('The Silence'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-18T18:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T20:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T22:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T22:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T18:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T18:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T20:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T18:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T20:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-22T18:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T20:20:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('Trautmann'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-22T17:30:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T20:00:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('(3D) Pokémon Meisterdetektiv Pikachu'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-18T14:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T17:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T20:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T14:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T17:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T20:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T14:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T17:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T20:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T14:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T17:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T20:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-22T14:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-22T17:20:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-22T20:25:00+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('Pokémon Meisterdetektiv Pikachu'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-18T13:00:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-18T15:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T13:00:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-19T15:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-20T15:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-21T15:10:00+02:00')),
                        ShowTime::from(new DateTimeImmutable('2019-05-22T15:10:00+02:00')),
                    ])
                ),
            ]),
        ];

        yield [
            'file' => file_get_contents(__DIR__ . '/_files/kinoprogramm-week-ahead.html'),
            'movies' => Shows::from([
                Show::from(
                    MovieName::from('(3D) Godzilla 2: King of the Monsters'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-29T20:15+02:00')),
                    ])
                ),
                Show::from(
                    MovieName::from('Rocketman'),
                    ShowTimes::from([
                        ShowTime::from(new DateTimeImmutable('2019-05-29T20:20:00+02:00')),
                    ])
                ),
            ]),
        ];
    }
}
