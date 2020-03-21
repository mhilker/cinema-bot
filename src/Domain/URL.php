<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class URL
{
    private string $value;

    /**
     * @throws InvalidUrlException
     */
    private function __construct(string $value)
    {
        if ($value === '') {
            throw new InvalidUrlException('URL must not be empty');
        }

        $this->value = $value;
    }

    /**
     * @throws InvalidUrlException
     */
    public static function from(string $value): self
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
