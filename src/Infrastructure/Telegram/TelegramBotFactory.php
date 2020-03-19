<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use TelegramBot\Api\BotApi;

final class TelegramBotFactory
{
    public function __invoke(): BotApi
    {
        $token = TelegramToken::get();

        return new BotApi($token);
    }
}
