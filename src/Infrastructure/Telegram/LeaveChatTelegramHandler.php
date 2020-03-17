<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Domain\GroupID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class LeaveChatTelegramHandler implements TelegramHandler
{
    public function handle(Client $bot, Update $update, GroupID $groupID): void
    {
        echo 'left chat' . PHP_EOL;
    }

    public function check(Update $update): bool
    {
        return $update->getMessage()->getLeftChatMember() !== null;
    }
}
