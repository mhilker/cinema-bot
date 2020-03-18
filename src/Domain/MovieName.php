<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class MovieName
{
    private string $value;

    /**
     * @throws InvalidMovieNameException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidMovieNameException('Movie eame must not be empty');
        }
        $this->value = $value;
    }

    /**
     * @throws InvalidMovieNameException
     */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function containsInsensitiveTerm(Term $term): bool
    {
        return mb_stripos($this->asString(), $term->asString()) !== false;
    }
}
