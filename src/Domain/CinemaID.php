<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use CinemaBot\Application\Aggregate\AggregateID;

final class CinemaID implements AggregateID
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): CinemaID
    {
        return new self($value);
    }

    public static function random(): CinemaID
    {
        $bytes = random_bytes(16);

        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40); // set version to 0100
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        $id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));

        return new self($id);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
