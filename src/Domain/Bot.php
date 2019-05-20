<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use TelegramBot\Api\BotApi;

class Bot
{
    /** @var BotApi */
    private $telegram;

    public function __construct(BotApi $telegram)
    {
        $this->telegram = $telegram;
    }

    public function help(string $chatId): void
    {
        $message = <<<MESSAGE
        `/help` Show this help.
        `/show` Show all items.
        `/add text` Add item.
        `/remove text` Remove item.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function show(string $chatId): void
    {
        $message = <<<MESSAGE
        
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function add(string $chatId, string $value): void
    {
        $message = <<<MESSAGE
        Added "{$value}" to watchlist.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function remove(string $chatId, string $value): void
    {
        $message = <<<MESSAGE
        Removed "{$value}" from watchlist.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }
}
