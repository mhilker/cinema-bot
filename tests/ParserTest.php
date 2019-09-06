<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use CinemaBot\Domain\AddShowToCinema\Parser\DOMParser;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\AddShowToCinema\Parser\DOMParser
 */
class ParserTest extends TestCase
{
    /**
     * @dataProvider provider
     *
     * @param ExistingFile $file
     * @param Movies $expectedMovies
     */
    public function testExtractsTimesForMovieFromHTML(ExistingFile $file, Movies $expectedMovies): void
    {
        $parser = new DOMParser();
        $movies = $parser->parse($file);

        $this->assertEquals($expectedMovies, $movies);
    }

    public function provider(): Generator
    {
        yield [
            'file' => ExistingFile::from(__DIR__ . '/_files/kinoprogramm.html'),
            'movies' => Movies::from([
                Movie::from(
                    MovieName::from('Asbury Park: Riot, Redemption, Rock N Roll'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-22T20:15:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('Aladdin'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-22T20:00:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('John Wick: Kapitel 3'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-22T20:20:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('The Silence'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-18T18:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T20:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T22:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T22:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T18:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T18:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T20:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T18:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T20:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-22T18:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T20:20:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('Trautmann'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-22T17:30:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T20:00:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('(3D) Pokémon Meisterdetektiv Pikachu'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-18T14:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T17:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T20:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T14:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T17:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T20:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T14:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T17:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T20:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T14:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T17:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T20:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-22T14:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-22T17:20:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-22T20:25:00+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('Pokémon Meisterdetektiv Pikachu'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-18T13:00:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-18T15:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T13:00:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-19T15:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-20T15:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-21T15:10:00+02:00')),
                        MovieTime::from(new DateTimeImmutable('2019-05-22T15:10:00+02:00')),
                    ])
                ),
            ]),
        ];

        yield [
            'file' => ExistingFile::from(__DIR__ . '/_files/kinoprogramm-week-ahead.html'),
            'movies' => Movies::from([
                Movie::from(
                    MovieName::from('(3D) Godzilla 2: King of the Monsters'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-29T20:15+02:00')),
                    ])
                ),
                Movie::from(
                    MovieName::from('Rocketman'),
                    MovieTimes::from([
                        MovieTime::from(new DateTimeImmutable('2019-05-29T20:20:00+02:00')),
                    ])
                ),
            ]),
        ];
    }
}
