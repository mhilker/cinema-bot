<?php

declare(strict_types=1);

namespace CinemaBot;

use DateTimeImmutable;
use DOMDocument;
use DOMXPath;

class Parser
{
    public function parse(string $fileName, string $movieName): Movie
    {
        $document = new DOMDocument();
        @$document->loadHTMLFile($fileName);
        $xpath = new DOMXPath($document);

        $times = $this->extractMovieTimes($movieName, $xpath);

        return new Movie($movieName, $times);
    }

    protected function extractMovieTimes(string $movieName, DOMXPath $xpath): MovieTimes
    {
        $movieTimes = new MovieTimes([]);

        $nodes = $xpath->query('//*[@id="program-module"]/table[2]/tbody/tr');

        $startDay = $this->extractStartDay($xpath);

        foreach ($nodes as $node) {
            $result = $xpath->query('td[@class="pmovie"]/h5', $node);
            $name = $result->item(0)->textContent;

            if ($name !== $movieName) {
                continue;
            }

            $days = $xpath->query('td[contains(concat(\' \', @class, \' \'), \' pday \')]', $node);
            foreach ($days as $j => $day) {
                $times = $xpath->query('span/a', $day);

                foreach ($times as $time) {
                    [$hours, $minutes] = explode(':', trim($time->textContent));

                    $dateTime = $startDay;
                    $dateTime = $dateTime->setTimezone(new \DateTimeZone('Europe/Berlin'));
                    $dateTime = $dateTime->add(new \DateInterval('P'. $j .'D'));
                    $dateTime = $dateTime->setTime((int) $hours, (int) $minutes);

                    $movieTimes->add(new MovieTime($dateTime));
                }
            }

            break;
        }

        return $movieTimes;
    }

    private function extractStartDay(DOMXPath $xpath): DateTimeImmutable
    {
        $nodes = $xpath->query('//*[@id="movieWeekSelect"]/option[@selected]');

        if ($nodes->length === 0) {
            return new DateTimeImmutable();
        }

        $content = trim($nodes->item(0)->textContent);

        preg_match('/(\d{2}).(\d{2}).(\d{4}) - \d{2}.\d{2}.\d{4}/', $content, $matches);

        $dateTime = new DateTimeImmutable();
        $dateTime = $dateTime->setDate((int) $matches[3], (int) $matches[2], (int) $matches[1]);
        return $dateTime;
    }
}
