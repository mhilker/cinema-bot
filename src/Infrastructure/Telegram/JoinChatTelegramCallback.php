<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Domain\GroupID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class JoinChatTelegramCallback implements TelegramCallback
{
    public function handle(Client $bot, Update $update, GroupID $groupID): void
    {
        echo 'joined chat' . PHP_EOL;
    }

    public function check(Update $update): bool
    {
        return $update->getMessage()->getNewChatMember() !== null;
    }
}
