<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\AddShowToCinema\Parser\WeekParser;
use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\URL;
use CinemaBot\Domain\URLs;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CinemaBot\Domain\AddShowToCinema\Parser\WeekParser
 */
class WeekParserTest extends TestCase
{
    public function testParsesUrlsFromHTML(): void
    {
        $file = ExistingFile::from(__DIR__ . '/_files/kinoprogramm.html');

        $parser = new WeekParser();
        $urls = $parser->parse($file);

        $expectedUrls = URLs::from([
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-05-23&cHash=bfdab51f2903cd2b7d1b424ab7c5caab'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-05-30&cHash=70b71c83e57313498748ed8af8b5f59b'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-06-20&cHash=feb5e21175babfd03c8db385287a9a0e'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-07-25&cHash=27dfa9d4ccbaa9038666c3219d9b62c0'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-10-10&cHash=93dcb79f1fb54fd4f7d4dc3768df20b5'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-10-24&cHash=8d3d851747f7699ff2ea7a219045fc20'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-11-07&cHash=3891b263f115d47d0a71cd7dbc1ca259'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2019-11-21&cHash=505a51e0f2d2219957dd508fd8eef2fd'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-01-09&cHash=e6861c42abc0ea2cc8b01b159dda8f1e'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-01-30&cHash=5e56dc014039f853a6003b9266e61018'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-02-27&cHash=c8ae8d30018be421a8c635658295af7d'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-03-12&cHash=789df4364d88001f6db5b61a175565ca'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-04-09&cHash=77258002837d6d3ccf2cfab30d21b971'),
            URL::from('https://www.cinemotion-kino.de/hameln/kinoprogramm?tx_ppicinemotionshowtime_showtime%5Bdate%5D=2020-05-07&cHash=e0e9e4de4405e1bbdd2a3c0b95262f2e'),
        ]);

        $this->assertEquals($expectedUrls, $urls);
    }
}
