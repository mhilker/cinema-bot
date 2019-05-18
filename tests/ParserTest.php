<?php

declare(strict_types=1);

namespace CinemaBot;

use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Parser
 */
class ParserTest extends TestCase
{
    /**
     * @dataProvider provider
     *
     * @param string $fileName
     * @param string $movieName
     * @param MovieTimes $expectedTimes
     */
    public function testExtractsTimesForMovieFromHTML(string $fileName, string $movieName, MovieTimes $expectedTimes): void
    {
        $parser = new Parser();
        $movie = $parser->parse($fileName, $movieName);

        $this->assertEquals($movieName, $movie->getName());
        $this->assertEquals($expectedTimes, $movie->getTimes());
    }

    public function provider(): Generator
    {
        yield [
            'file_name' => __DIR__ . '/kinoprogramm.html',
            'movie_name' => '(3D) Pokémon Meisterdetektiv Pikachu',
            'expected_times' => new MovieTimes([
                new MovieTime(new DateTimeImmutable('2019-05-18T14:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-18T17:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-18T20:10+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-19T14:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-19T17:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-19T20:10+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-20T14:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-20T17:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-20T20:10+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-21T14:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-21T17:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-21T20:10+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-22T14:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-22T17:20+02:00')),
                new MovieTime(new DateTimeImmutable('2019-05-22T20:25+02:00')),
            ]),
        ];

        yield [
            'file_name' => __DIR__ . '/kinoprogramm-week-ahead.html',
            'movie_name' => '(3D) Godzilla 2: King of the Monsters',
            'expected_times' => new MovieTimes([
                new MovieTime(new DateTimeImmutable('2019-05-29T20:15+02:00')),
            ]),
        ];

        yield [
            'file_name' => __DIR__ . '/kinoprogramm-week-ahead.html',
            'movie_name' => 'Rocketman',
            'expected_times' => new MovieTimes([
                new MovieTime(new DateTimeImmutable('2019-05-29T20:20+02:00')),
            ]),
        ];
    }
}
