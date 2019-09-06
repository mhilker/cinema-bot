<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Movie;
use DateTimeImmutable;
use IntlDateFormatter;
use TelegramBot\Api\BotApi;

final class TelegramNotifier implements Notifier
{
    /** @var BotApi */
    private $telegram;

    public function __construct(BotApi $telegram)
    {
        $this->telegram = $telegram;
    }

    public function send(Movie $movie, ChatID $chatId): void
    {
        $message = $this->formatMessage($movie);

        $this->telegram->sendMessage($chatId->asString(), $message, 'markdown');
    }

    protected function formatMessage(Movie $movie): string
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

            foreach ($times as $time) {
                $formatter->setPattern('HH:mm');
                $message .= '`' . $formatter->format($time->getValue()) . '`' . PHP_EOL;
            }
        }

        return $message;
    }
}
