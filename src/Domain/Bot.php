<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Domain\Watchlist\Watchlist;
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
        `/show` Show all items on watchlist.
        `/add text` Add item to watchlist.
        `/remove text` Remove item from watchlist.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function show(string $chatId, Watchlist $watchlist): void
    {
        if (count($watchlist) > 0) {
            $message = 'Current watchlist:' . PHP_EOL;
            foreach ($watchlist as $term) {
                $message .=  '`' . $term->asString() . '`' . PHP_EOL;
            }
        } else {
            $message = 'The current watchlist is empty.';
        }

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function add(string $chatId, string $value): void
    {
        $message = <<<MESSAGE
        Added `{$value}` to watchlist.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }

    public function remove(string $chatId, string $value): void
    {
        $message = <<<MESSAGE
        Removed `{$value}` from watchlist.
        MESSAGE;

        $this->telegram->sendMessage($chatId, $message, 'markdown', true);
    }
}
