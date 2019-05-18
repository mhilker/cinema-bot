<?php

declare(strict_types=1);

namespace CinemaBot;

use DateTimeImmutable;
use IntlDateFormatter;
use TelegramBot\Api\BotApi;

class Notifier
{
    /** @var BotApi */
    private $telegram;

    public function __construct(BotApi $telegram)
    {
        $this->telegram = $telegram;
    }

    public function send(Movie $movie, string $chatId): void
    {
        $message = $this->formatMessage($movie);

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    protected function formatMessage(Movie $movie): string
    {
        $perDay = [];
        foreach ($movie->getTimes() as $time) {
            $perDay[$time->getDay()][] = $time;
        }

        $formatter = new IntlDateFormatter(
            'de_DE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Berlin',
            IntlDateFormatter::GREGORIAN
        );

        $message = $movie->getName() . PHP_EOL;
        foreach ($perDay as $day => $times) {
            $datetime = new DateTimeImmutable($day);

            $formatter->setPattern('EEEE (dd.MM.yyyy)');
            $message .= '*' . $formatter->format($datetime) . '*:' . PHP_EOL;

            foreach ($times as $time) {
                $formatter->setPattern('HH:mm');
                $message .= '`' . $formatter->format($time->getDateTime()) . '`' . PHP_EOL;
            }
        }

        return $message;
    }
}
