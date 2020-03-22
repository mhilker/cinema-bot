<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\Group\ChatID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class HelpTelegramCommand implements TelegramCommand
{
    public function getName(): string
    {
        return 'help';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());

        $response = <<< MESSAGE
        *Commands:*
        `/help` Show this help.
        `/movies` Show all known movies.
        `/show` Show all terms on watch list.
        `/add [term]` Add term to watch list.
        `/remove [term]` Remove term from watch list.
        `/about` Show various statistics.
        MESSAGE;

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
