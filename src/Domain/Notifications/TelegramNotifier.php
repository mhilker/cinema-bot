<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Show;
use DateTimeImmutable;
use IntlDateFormatter;
use TelegramBot\Api\BotApi;

final class TelegramNotifier implements Notifier
{
    private ChatGroupMapProjection $projection;
    private BotApi $telegram;

    public function __construct(ChatGroupMapProjection $projection, BotApi $telegram)
    {
        $this->projection = $projection;
        $this->telegram = $telegram;
    }

    public function notify(Notification $notification): void
    {
        $chatID = $this->projection->loadChatIDByGroupID($notification->getGroupID());

        $messages = [];
        foreach ($notification->getShows() as $show) {
            $messages[] = $this->format($show);
        }
        $message = implode(PHP_EOL, $messages);

        $this->telegram->sendMessage($chatID->asString(), $message, 'markdown');
    }

    private function format(Show $movie): string
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
