<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Watchlist;

final class Term
{
    /** @var string */
    private $value;

    private function __construct(string $term)
    {
        if ($term === '') {
            throw new InvalidTermException(sprintf('Term "%s" is invalid.', $term));
        }

        $this->value = $term;
    }

    public static function from(string $term): Term
    {
        return new self($term);
    }

    public function asString(): string
    {
        return $this->value;
    }
}