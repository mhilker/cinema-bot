<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Domain\GroupID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

interface TelegramHandler
{
    public const PARSE_MODE = 'markdown';

    public function handle(Client $bot, Update $update, GroupID $groupID): void;

    public function check(Update $update): bool;
}
