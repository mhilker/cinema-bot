<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Infrastructure\Telegram\TelegramToken;
use TelegramBot\Api\Client;

final class TelegramFactory
{
    public function __invoke(): Client
    {
        $token = TelegramToken::get();

        return new Client($token);
    }
}
