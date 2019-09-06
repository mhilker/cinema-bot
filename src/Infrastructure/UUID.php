<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

final class UUID
{
    public static function generateV4(): string
    {
        $bytes = random_bytes(16);

        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40); // set version to 0100
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
