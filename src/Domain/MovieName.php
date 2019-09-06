<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class MovieName
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): MovieName
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function containsInsensitiveTerm(string $term): bool
    {
        return mb_stripos($this->asString(), $term) !== false;
    }
}
