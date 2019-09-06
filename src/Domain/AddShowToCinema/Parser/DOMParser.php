<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Parser;

use CinemaBot\Domain\ExistingFile;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use DOMDocument;
use DOMXPath;

final class DOMParser implements Parser
{
    public function parse(ExistingFile $fileName): Movies
    {
        $document = new DOMDocument();
        @$document->loadHTMLFile($fileName->asString());
        $xpath = new DOMXPath($document);

        return $this->extractMovies($xpath);
    }

    protected function extractMovies(DOMXPath $xpath): Movies
    {
        $movies = Movies::from([]);

        $nodes = $xpath->query('//*[@id="program-module"]/table[2]/tbody/tr');

        $startDay = $this->extractStartDay($xpath);

        foreach ($nodes as $node) {
            $movieTimes = new MovieTimes([]);

            $result = $xpath->query('td[@class="pmovie"]/h5', $node);
            $movieName = MovieName::from($result->item(0)->textContent);

            $days = $xpath->query('td[contains(concat(\' \', @class, \' \'), \' pday \')]', $node);
            foreach ($days as $j => $day) {
                $times = $xpath->query('span/a', $day);

                foreach ($times as $time) {
                    [$hours, $minutes] = explode(':', trim($time->textContent));

                    $dateTime = $startDay;
                    $dateTime = $dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));
                    $dateTime = $dateTime->add(new DateInterval('P'. $j .'D'));
                    $dateTime = $dateTime->setTime((int) $hours, (int) $minutes);

                    $movieTimes->add(new MovieTime($dateTime));
                }
            }

            $movies->add(new Movie($movieName, $movieTimes));
        }

        return $movies;
    }

    private function extractStartDay(DOMXPath $xpath): DateTimeImmutable
    {
        $nodes = $xpath->query('//*[@id="movieWeekSelect"]/option[@selected]');

        if ($nodes->length === 0) {
            $nodes = $xpath->query('//*[@id="movieWeekSelect"]/option[2]');
            $content = trim($nodes->item(0)->textContent);

            $dateTime = $this->parseDateTime($content);
            $dateTime = $dateTime->sub(new DateInterval('P5D'));
            return $dateTime;
        }

        $content = trim($nodes->item(0)->textContent);

        return $this->parseDateTime($content);
    }

    private function parseDateTime(string $content): DateTimeImmutable
    {
        preg_match('/(\d{2}).(\d{2}).(\d{4})\s+-\s+\d{2}.\d{2}.\d{4}/', $content, $matches);

        $dateTime = new DateTimeImmutable();
        $dateTime = $dateTime->setDate((int)$matches[3], (int)$matches[2], (int)$matches[1]);
        return $dateTime;
    }
}
