<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

class TelegramToken
{
    public static function get(): string
    {
        $file = getenv('TELEGRAM_TOKEN_FILE');

        if ($file === false) {
            return getenv('TELEGRAM_TOKEN');
        }

        return trim(file_get_contents($file));
    }
}
