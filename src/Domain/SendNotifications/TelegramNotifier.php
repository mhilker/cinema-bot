<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Movie;
use TelegramBot\Api\BotApi;

final class TelegramNotifier implements Notifier
{
    private BotApi $telegram;
    private NotificationFormatter $formatter;

    public function __construct(BotApi $telegram, NotificationFormatter $formatter)
    {
        $this->telegram = $telegram;
        $this->formatter = $formatter;
    }

    public function send(Movie $movie, ChatID $chatId): void
    {
        $message = $this->formatter->format($movie);

        $this->telegram->sendMessage($chatId->asString(), $message, 'markdown');
    }
}
