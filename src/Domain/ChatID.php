<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class ChatID
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): ChatID
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
