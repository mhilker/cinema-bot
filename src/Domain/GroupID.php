<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class GroupID
{
    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): GroupID
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
