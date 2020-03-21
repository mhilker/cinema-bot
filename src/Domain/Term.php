<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class Term
{
    private string $value;

    /**
     * @throws InvalidTermException
     */
    private function __construct(string $term)
    {
        if ($term === '') {
            throw new InvalidTermException(sprintf('Term "%s" is invalid.', $term));
        }

        $this->value = $term;
    }

    /**
     * @throws InvalidTermException
     */
    public static function from(string $term): Term
    {
        return new self($term);
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function equals(Term $term): bool
    {
        return $this->value === $term->value;
    }

    public function notEquals(Term $term): bool
    {
        return $this->equals($term) === false;
    }
}
