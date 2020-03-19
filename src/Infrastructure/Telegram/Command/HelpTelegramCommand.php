<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\ChatID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

class HelpTelegramCommand implements TelegramCommand
{
    public function getName(): string
    {
        return 'help';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());

        $response = <<< MESSAGE
        `/help` Show this help.
        `/show` Show all terms on watchlist.
        `/add [term]` Add term to watchlist.
        `/remove [term]` Remove term from watchlist.
        MESSAGE;

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
