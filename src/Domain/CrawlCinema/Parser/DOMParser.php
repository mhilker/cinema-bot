<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema\Parser;

use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Show;
use CinemaBot\Domain\Shows;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\ShowTimes;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use DOMDocument;
use DOMXPath;

final class DOMParser implements Parser
{
    public function parse(string $html): Shows
    {
        $document = new DOMDocument();
        @$document->loadHTML($html);
        $xpath = new DOMXPath($document);

        return $this->extractMovies($xpath);
    }

    protected function extractMovies(DOMXPath $xpath): Shows
    {
        $movies = [];
        $nodes = $xpath->query('//*[@id="program-module"]/table[2]/tbody/tr');
        $startDay = $this->extractStartDay($xpath);

        foreach ($nodes as $node) {
            $movieTimes = [];

            $result = $xpath->query('td[@class="pmovie"]/h5', $node);
            $movieName = MovieName::from($result->item(0)->textContent);

            $days = $xpath->query('td[contains(concat(\' \', @class, \' \'), \' pday \')]', $node);
            foreach ($days as $j => $day) {
                $times = $xpath->query('span/a', $day);

                foreach ($times as $time) {
                    [$hours, $minutes] = explode(':', trim($time->textContent));

                    $dateTime = $startDay;
                    $dateTime = $dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));
                    $dateTime = $dateTime->add(new DateInterval('P' . $j . 'D'));
                    $dateTime = $dateTime->setTime((int) $hours, (int) $minutes);

                    $movieTimes[] = ShowTime::from($dateTime);
                }
            }

            $movies[] = Show::from($movieName, ShowTimes::from($movieTimes));
        }

        return Shows::from($movies);
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
