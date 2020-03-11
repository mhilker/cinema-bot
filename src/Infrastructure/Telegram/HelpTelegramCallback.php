<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Domain\GroupID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class HelpTelegramCallback implements TelegramCallback
{
    public function handle(Client $bot, Update $update, GroupID $groupID): void
    {
        $response = <<< MESSAGE
        `/help` Show this help.
        `/show` Show all terms on watchlist.
        `/add [term]` Add term to watchlist.
        `/remove [term]` Remove term from watchlist.
        MESSAGE;

        $bot->sendMessage($update->getMessage()->getChat()->getId(), $response, self::PARSE_MODE);
    }

    public function check(Update $update): bool
    {
        return $update->getMessage()->getText() === '/help';
    }
}
