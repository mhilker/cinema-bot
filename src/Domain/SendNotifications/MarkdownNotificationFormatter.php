<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\Movie;
use DateTimeImmutable;
use IntlDateFormatter;

final class MarkdownNotificationFormatter implements NotificationFormatter
{
    public function format(Movie $movie): string
    {
        $perDay = [];
        foreach ($movie->getTimes() as $time) {
            $perDay[$time->getValue()->format('Y-m-d')][] = $time;
        }

        $formatter = new IntlDateFormatter(
            'de_DE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Berlin',
            IntlDateFormatter::GREGORIAN
        );

        $message = $movie->getName()->asString() . PHP_EOL;
        foreach ($perDay as $day => $times) {
            $datetime = new DateTimeImmutable($day);

            $formatter->setPattern('EEEE (dd.MM.yyyy)');
            $message .= '*' . $formatter->format($datetime) . '*:' . PHP_EOL;

            $formatter->setPattern('HH:mm');
            foreach ($times as $time) {
                $message .= '`' . $formatter->format($time->getValue()) . '`' . PHP_EOL;
            }
        }

        return $message;
    }
}
