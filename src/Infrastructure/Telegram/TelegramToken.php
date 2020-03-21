<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

final class TelegramToken
{
    public static function get(): string
    {
        $file = getenv('TELEGRAM_TOKEN_FILE');
        if (!file_exists($file)) {
            throw new TokenException('Telegram token file does not exist');
        }

        $contents = file_get_contents($file);
        if ($contents === false) {
            throw new TokenException('Could not read telegram token file');
        }

        return trim($contents);
    }
}
