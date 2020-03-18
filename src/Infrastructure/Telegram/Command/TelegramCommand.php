<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\GroupID;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

interface TelegramCommand
{
    public function getName(): string;

    public function execute(Client $bot, Message $message): void;
}
