<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Domain\Watchlist\Term;
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

    public function help(ChatID $chatID): void
    {
        $message = <<< MESSAGE
        `/help` Show this help.
        `/show` Show all terms on watchlist.
        `/add [term]` Add term to watchlist.
        `/remove [term]` Remove term from watchlist.
        MESSAGE;

//        $this->telegram->sendMessage($chatID->asString(), $message, 'markdown');
    }

    public function show(ChatID $chatID, Watchlist $watchlist): void
    {
        if (count($watchlist) > 0) {
            $message = 'Current watchlist:' . PHP_EOL;
            foreach ($watchlist as $term) {
                $message .=  '`' . $term->asString() . '`' . PHP_EOL;
            }
        } else {
            $message = 'The current watchlist is empty.';
        }

//        $this->telegram->sendMessage($chatID->asString(), $message, 'markdown');
    }

    public function add(ChatID $chatID, Term $term): void
    {
        $message = 'Added `' . $term->asString() . '` to watchlist.';

//        $this->telegram->sendMessage($chatID->asString(), $message, 'markdown');
    }

    public function remove(ChatID $chatID, Term $term): void
    {
        $message = 'Removed `' . $term->asString() . '` from watchlist.';

//        $this->telegram->sendMessage($chatID->asString(), $message, 'markdown');
    }
}
